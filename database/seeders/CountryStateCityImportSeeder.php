<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountryStateCityImportSeeder extends Seeder
{
    public function run(): void
    {
        // Load JSON file
        $jsonPath = storage_path('app/public/countries-states-cities.json');

        // Check if file exists
        if (!File::exists($jsonPath)) {
            dd("File not found: " . $jsonPath);
        }

        // Read and decode JSON
        $json = File::get($jsonPath);
        $countries = json_decode($json, true);

        // Verify JSON structure
        if (!$countries || !is_array($countries)) {
            dd("Invalid JSON structure.");
        }

        // Clear existing data
        // DB::table('countries')->truncate();
        // DB::table('states')->truncate();
        // DB::table('cities')->truncate();
        DB::table('countries')->delete();
        DB::table('states')->delete();
        DB::table('cities')->delete();


        foreach ($countries as $country) {
            $country_id = DB::table('countries')->insertGetId([
                'name' => $country['name'] ?? 'Unknown',
                'code' => $country['iso2'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($country['states'] ?? [] as $state) {
                $state_id = DB::table('states')->insertGetId([
                    'name' => $state['name'] ?? 'Unknown',
                    'country_id' => $country_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($state['cities'] ?? [] as $city) {
                    DB::table('cities')->insert([
                        'name' => $city['name'] ?? 'Unknown',
                        'state_id' => $state_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        echo "✅ Countries, states, and cities imported successfully!";
    }
}
