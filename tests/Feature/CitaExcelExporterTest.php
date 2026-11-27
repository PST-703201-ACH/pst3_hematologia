<?php

/* Verifies the Excel report layout, preserved data, and literal cell values. */
use App\Services\CitaExcelExporter;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

it('creates a styled XLSX report while preserving exported appointment data', function () {
    $citas = collect([[
        'fecha_hora' => '26/11/2026 10:30',
        'numero_hc' => '000123',
        'paciente' => '=HYPERLINK("https://example.test")',
        'representante' => 'Ana Pérez',
        'consulta' => 'Consulta #12 - Anemia',
        'conteo_fecha' => 3,
    ]]);

    $libro = app(CitaExcelExporter::class)->generar(
        $citas,
        Carbon::parse('2026-11-27 01:00:00')
    );
    $hoja = $libro->getActiveSheet();

    expect($hoja->getCell('A1')->getValue())->toBe('REPORTE DE CITAS REGISTRADAS')
        ->and($hoja->getCell('A2')->getValue())->toBe('Generado el: 27/11/2026 01:00')
        ->and($hoja->getCell('A4')->getValue())->toBe('Fecha y hora (cita)')
        ->and($hoja->getCell('F4')->getValue())->toBe('Citas registradas en la fecha')
        ->and($hoja->getCell('A5')->getValue())->toBe('26/11/2026 10:30')
        ->and($hoja->getCell('B5')->getValue())->toBe('000123')
        ->and($hoja->getCell('C5')->getValue())->toBe('=HYPERLINK("https://example.test")')
        ->and($hoja->getCell('C5')->getDataType())->toBe(DataType::TYPE_STRING)
        ->and($hoja->getCell('D5')->getValue())->toBe('Ana Pérez')
        ->and($hoja->getCell('E5')->getValue())->toBe('Consulta #12 - Anemia')
        ->and($hoja->getCell('F5')->getValue())->toBe(3)
        ->and($hoja->getStyle('A4')->getFill()->getStartColor()->getRGB())->toBe('007BFF')
        ->and($hoja->getColumnDimension('A')->getWidth())->toBe(23.0)
        ->and($hoja->getAutoFilter()->getRange())->toBe('A4:F5')
        ->and($hoja->getPageSetup()->getOrientation())->toBe(PageSetup::ORIENTATION_LANDSCAPE);

    $archivo = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'reporte-citas-' . bin2hex(random_bytes(8)) . '.xlsx';

    try {
        (new Xlsx($libro))->save($archivo);
        $libroDescargado = IOFactory::load($archivo);
        $hojaDescargada = $libroDescargado->getActiveSheet();

        expect($hojaDescargada->getCell('A1')->getValue())->toBe('REPORTE DE CITAS REGISTRADAS')
            ->and($hojaDescargada->getCell('B5')->getValue())->toBe('000123')
            ->and($hojaDescargada->getCell('C5')->getDataType())->toBe(DataType::TYPE_STRING)
            ->and($hojaDescargada->getStyle('A4')->getFill()->getStartColor()->getRGB())->toBe('007BFF')
            ->and($hojaDescargada->getColumnDimension('A')->getWidth())->toBe(23.0);
    } finally {
        if (is_file($archivo)) {
            unlink($archivo);
        }
    }
});

it('shows a clear message when there are no appointments to export', function () {
    $hoja = app(CitaExcelExporter::class)
        ->generar(collect(), Carbon::parse('2026-11-27 01:00:00'))
        ->getActiveSheet();

    expect($hoja->getCell('A5')->getValue())->toBe('No hay citas registradas.')
        ->and($hoja->getAutoFilter()->getRange())->toBe('A4:F4');
});

it('downloads the administrative report as an XLSX file', function () {
    DB::purge();

    try {
        DB::statement("ATTACH DATABASE ':memory:' AS logistica");
        DB::statement('CREATE TABLE logistica.cita (
            cita_id INTEGER PRIMARY KEY,
            nombres_paciente TEXT,
            apellidos_paciente TEXT,
            nombres_representante TEXT,
            apellidos_representante TEXT,
            numero_hc TEXT,
            fecha_hora TEXT,
            consulta_id INTEGER
        )');

        $usuario = new User();
        $usuario->username = 'administrativo.test';
        $usuario->id_rol = 2;

        $respuesta = $this->actingAs($usuario)->get(route('citas.exportar.excel'));

        $respuesta->assertOk()
            ->assertDownload()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->assertHeaderContains('content-disposition', '.xlsx');

        expect(substr($respuesta->streamedContent(), 0, 2))->toBe('PK');
    } finally {
        DB::purge();
    }
});
