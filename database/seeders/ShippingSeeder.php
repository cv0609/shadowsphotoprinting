<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Shipping::create([
            'country' => 'Australia',
            'shipping_method' => 0,
            'amount' => 20,
            'status' => 1
        ]);
    }
}
