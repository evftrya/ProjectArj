<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportDataRajaOngkir extends Seeder
{
    public function run()
    {
        $path = database_path('sql/db_rajaongkir.sql');

        if (!File::exists($path)) {
            $this->command->error("SQL file not found at: $path");
            return;
        }

        $sql = File::get($path);
        DB::unprepared($sql);

        $this->command->info('SQL file has been imported successfully!');
    }
}
