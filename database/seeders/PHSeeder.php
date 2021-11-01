<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Ph;
use App\Models\Rks;
use Illuminate\Database\Seeder;

class PHSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ph::factory(40)
            ->has(Admin::factory())
            ->create();
    }
}
