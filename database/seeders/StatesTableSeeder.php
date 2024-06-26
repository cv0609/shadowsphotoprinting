<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countryId = 14;
        $states = [
            ['name' => 'New South Wales', 'code' => 'NSW', 'country_id' => $countryId],
            ['name' => 'Victoria', 'code' => 'VIC','country_id' => $countryId],
            ['name' => 'Queensland', 'code' => 'QLD','country_id' => $countryId],
            ['name' => 'South Australia', 'code' => 'SA','country_id' => $countryId],
            ['name' => 'Western Australia', 'code' => 'WA','country_id' => $countryId],
            ['name' => 'Tasmania', 'code' => 'TAS','country_id' => $countryId],
            ['name' => 'Northern Territory', 'code' => 'NT','country_id' => $countryId],
            ['name' => 'Australian Capital Territory', 'code' => 'ACT','country_id' => $countryId],
        ];

        DB::table('states')->insert($states);
    }
}
