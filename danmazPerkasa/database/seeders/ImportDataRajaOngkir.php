<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportDataRajaOngkir extends Seeder
{
    public function run()
    {
        $path = database_path('sql/1-master_data.sql');

        if (!File::exists($path)) {
            $this->command->error("SQL file not found at: $path");
            return;
        }

        $sql = File::get($path);
        DB::unprepared($sql);

        $this->command->info('SQL-base file has been imported successfully!');
        $this->import_part();
    }

    public function import_part(){
        $path = database_path('sql/2-dp-part-seed.sql');

        if (!File::exists($path)) {
            $this->command->error("SQL Part not found: $path");
            return;
        }

        $sql = File::get($path);
        DB::unprepared($sql);

        $this->command->info('SQL Part file has been imported successfully!');
    }
}
