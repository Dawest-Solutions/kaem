<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Ph;
use App\Models\Rks;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class RKSSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rks = [
            '',
            '',
            '',
            '',
        ];

        foreach ($rks as $name) {
            Rks::create([
                'name' => $name,
            ]);
        }

    }
}
