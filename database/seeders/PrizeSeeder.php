<?php

namespace Database\Seeders;

use App\Models\Prize;
use App\Models\PrizeCategory;
use Illuminate\Database\Seeder;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PrizeCategory::factory(7)->create();
        Prize::factory(20)->create();
    }
}
