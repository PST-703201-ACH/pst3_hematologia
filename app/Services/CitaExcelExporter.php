<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

/**
 * Construye la hoja de cálculo de citas con el estilo del reporte PDF.
 */
class CitaExcelExporter
{
    public function generar(Collection $citas, Carbon $fechaEmision): Spreadsheet
    {
        $libro = new Spreadsheet();
        $hoja = $libro->getActiveSheet();
        $hoja->setTitle('Citas');
        $hoja->setShowGridlines(false);

        $libro->getProperties()
            ->setCreator('Sistema de Hematología')
            ->setTitle('Reporte de citas registradas')
            ->setSubject('Citas registradas');

        $hoja->mergeCells('A1:F1');
        $hoja->setCellValue('A1', 'REPORTE DE CITAS REGISTRADAS');
        $hoja->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 16],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '17365D']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $hoja->getRowDimension(1)->setRowHeight(30);

        $hoja->mergeCells('A2:F2');
        $hoja->setCellValue('A2', 'Generado el: ' . $fechaEmision->format('d/m/Y H:i'));
        $hoja->getStyle('A2:F2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '44546A']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EAF2F8']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $hoja->getRowDimension(2)->setRowHeight(22);

        $encabezados = [
            'Fecha y hora (cita)',
            'Número de historia clínica',
            'Nombre del paciente',
            'Nombre del representante',
            'Consulta asociada',
            'Citas registradas en la fecha',
        ];

        foreach ($encabezados as $indice => $encabezado) {
            $columna = chr(ord('A') + $indice);
            $hoja->setCellValue($columna . '4', $encabezado);
        }

        $hoja->getStyle('A4:F4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '007BFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '17365D']],
            ],
        ]);
        $hoja->getRowDimension(4)->setRowHeight(34);

        $citas = $citas->values();
        foreach ($citas as $indice => $cita) {
            $fila = $indice + 5;
            $valores = [
                $cita['fecha_hora'],
                $cita['numero_hc'],
                $cita['paciente'],
                $cita['representante'],
                $cita['consulta'],
            ];

            foreach ($valores as $columna => $valor) {
                $letraColumna = chr(ord('A') + $columna);
                $hoja->setCellValueExplicit(
                    $letraColumna . $fila,
                    (string) $valor,
                    DataType::TYPE_STRING
                );
            }

            $hoja->setCellValueExplicit(
                'F' . $fila,
                (int) $cita['conteo_fecha'],
                DataType::TYPE_NUMERIC
            );
            $hoja->getRowDimension($fila)->setRowHeight(30);

            if ($indice % 2 === 1) {
                $hoja->getStyle('A' . $fila . ':F' . $fila)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F4F6F9');
            }
        }

        $ultimaFila = max(5, $citas->count() + 4);
        if ($citas->isEmpty()) {
            $hoja->mergeCells('A5:F5');
            $hoja->setCellValue('A5', 'No hay citas registradas.');
        }

        $hoja->getStyle('A5:F' . $ultimaFila)->applyFromArray([
            'font' => ['color' => ['rgb' => '343A40']],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DEE2E6']],
            ],
        ]);
        if ($citas->isEmpty()) {
            $hoja->getStyle('A5:F5')->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '6C757D']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
        } else {
            $hoja->getStyle('F5:F' . $ultimaFila)->getNumberFormat()->setFormatCode('#,##0');
        }

        foreach (['A' => 23, 'B' => 24, 'C' => 32, 'D' => 32, 'E' => 40, 'F' => 28] as $columna => $ancho) {
            $hoja->getColumnDimension($columna)->setWidth($ancho);
        }

        $hoja->freezePane('A5');
        $hoja->getAutoFilter()->setRange('A4:F' . max(4, $citas->count() + 4));
        $hoja->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0)
            ->setRowsToRepeatAtTopByStartAndEnd(1, 4)
            ->setPrintArea('A1:F' . $ultimaFila);
        $hoja->getPageSetup()->setFitToPage(true);
        $hoja->getPageMargins()
            ->setTop(0.5)
            ->setRight(0.3)
            ->setBottom(0.5)
            ->setLeft(0.3);
        $hoja->getHeaderFooter()->setOddFooter('&LReporte de citas&RPágina &P de &N');

        return $libro;
    }
}
