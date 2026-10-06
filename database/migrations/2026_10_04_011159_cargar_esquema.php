<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        $path = database_path('estructura.sql');

        if (!File::exists($path)) {
            throw new \Exception("No se encontró el archivo 'estructura.sql'");
        }

        $esquemasExistentes = DB::select("
            SELECT schema_name 
            FROM information_schema.schemata 
            WHERE schema_name NOT IN ('public', 'information_schema', 'pg_catalog', 'pg_toast')
        ");

        foreach ($esquemasExistentes as $esquema) {
            DB::unprepared('DROP SCHEMA IF EXISTS "' . $esquema->schema_name . '" CASCADE;');
        }

        $sql = File::get($path);
        $sql = preg_replace('/--.*\n/', '', $sql);
        
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($statements as $statement) {
            if (!empty($statement)) {
                DB::unprepared($statement . ';');
            }
        }
    }
};
