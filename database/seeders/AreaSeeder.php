<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Support\Facades\File;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // === Provinces ===
        $provinces = json_decode(File::get(database_path('data/list_of_area/provinces.json')), true);
        foreach ($provinces as $item) {
            Province::updateOrCreate(
                ['id' => $item['id']],
                ['name' => $item['name']]
            );
        }

        // === Regencies ===
        $regencies = json_decode(File::get(database_path('data/list_of_area/regencies.json')), true);
        foreach ($regencies as $item) {
            Regency::updateOrCreate(
                ['id' => $item['id']],
                [
                    'province_id' => $item['province_id'],
                    'name' => $item['name']
                ]
            );
        }

        // === Districts ===
        $districts = json_decode(File::get(database_path('data/list_of_area/districts.json')), true);
        foreach ($districts as $item) {
            District::updateOrCreate(
                ['id' => $item['id']],
                [
                    'regency_id' => $item['regency_id'],
                    'name' => $item['name']
                ]
            );
        }

        // === Villages ===
        $villages = json_decode(File::get(database_path('data/list_of_area/villages.json')), true);
        foreach ($villages as $item) {
            Village::updateOrCreate(
                ['id' => $item['id']],
                [
                    'district_id' => $item['district_id'],
                    'name' => $item['name']
                ]
            );
        }

        $this->command->info("Data wilayah berhasil dimasukkan.");
    }
}
