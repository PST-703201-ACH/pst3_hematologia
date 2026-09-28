<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement(<<<'SQL'
            SELECT setval(
                pg_get_serial_sequence('persona', 'persona_id'),
                COALESCE((SELECT MAX(persona_id) FROM persona), 1),
                EXISTS(SELECT 1 FROM persona)
            )
        SQL);

        DB::statement(<<<'SQL'
            SELECT setval(
                pg_get_serial_sequence('usuario', 'usuario_id'),
                COALESCE((SELECT MAX(usuario_id) FROM usuario), 1),
                EXISTS(SELECT 1 FROM usuario)
            )
        SQL);
    }

    public function down(): void
    {
    }
};