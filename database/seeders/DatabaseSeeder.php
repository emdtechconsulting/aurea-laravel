<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([['Petipán con pollo', 250], ['Mini triple mixto', 300], ['Chicha morada 300 ml', 350]] as [$name,$price]) {
            Product::firstOrCreate(['name' => $name], ['price_cents' => $price, 'active' => true]);
        }
    }
}
