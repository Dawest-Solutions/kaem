<?php

namespace Database\Seeders;

use App\Models\OrderStatuses;
use App\Models\PrizeCategory;
use Illuminate\Database\Seeder;

class PrizeCategoriesSeeder extends Seeder
{
    public const prizeCategories = [
        'Auto i warsztat',
        'Dom i ogród',
        'Komputery - Foto',
        'Nagrody specjalne',
        'Smartfony i gadżety',
        'Sport i wypoczynek',
        'Sprzęt AGD',
        'Sprzęt RTV',
        'Zdrowie i uroda',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::prizeCategories as $category) {
            PrizeCategory::updateOrCreate([
                'name' => $category,
            ]);
        }
    }
}
