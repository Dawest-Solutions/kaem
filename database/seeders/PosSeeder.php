<?php

namespace Database\Seeders;

use App\Models\Pos;
use Illuminate\Database\Seeder;

class PosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pos::factory(20)->create();
    }
}
