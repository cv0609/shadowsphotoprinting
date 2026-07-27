<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $countryId = DB::table('countries')->where('code', 'NZ')->value('id');

        if (!$countryId) {
            return;
        }

        foreach ($this->states() as $state) {
            $alreadyExists = DB::table('states')
                ->where('country_id', $countryId)
                ->where('code', $state['code'])
                ->exists();

            if (!$alreadyExists) {
                DB::table('states')->insert([
                    'country_id' => $countryId,
                    'code' => $state['code'],
                    'name' => $state['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $countryId = DB::table('countries')->where('code', 'NZ')->value('id');

        if (!$countryId) {
            return;
        }

        DB::table('states')
            ->where('country_id', $countryId)
            ->whereIn('code', array_column($this->states(), 'code'))
            ->delete();
    }

    private function states(): array
    {
        return [
            ['name' => 'Northland', 'code' => 'NTL'],
            ['name' => 'Auckland', 'code' => 'AUK'],
            ['name' => 'Waikato', 'code' => 'WKO'],
            ['name' => 'Bay of Plenty', 'code' => 'BOP'],
            ['name' => 'Gisborne', 'code' => 'GIS'],
            ['name' => "Hawke's Bay", 'code' => 'HKB'],
            ['name' => 'Taranaki', 'code' => 'TKI'],
            ['name' => 'Manawatū-Whanganui', 'code' => 'MWT'],
            ['name' => 'Wellington', 'code' => 'WGN'],
            ['name' => 'Tasman', 'code' => 'TAS'],
            ['name' => 'Nelson', 'code' => 'NSN'],
            ['name' => 'Marlborough', 'code' => 'MBH'],
            ['name' => 'West Coast', 'code' => 'WTC'],
            ['name' => 'Canterbury', 'code' => 'CAN'],
            ['name' => 'Otago', 'code' => 'OTA'],
            ['name' => 'Southland', 'code' => 'STL'],
        ];
    }
};
