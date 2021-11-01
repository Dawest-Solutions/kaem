<?php

namespace Database\Seeders;

use App\Models\Pos;
use App\Models\Result;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Result::factory(15)->create();
    }
}
