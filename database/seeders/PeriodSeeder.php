<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public const PERIODS = [
        [
            'name' => 'czerwiec',
            'number' => 2,
            'begin_at' => '2021.06.01',
            'end_at' => '2021.06.30',
        ], [
            'name' => 'lipiec, sierpień, wrzesień',
            'number' => 3,
            'begin_at' => '2021.07.01',
            'end_at' => '2021.09.30',
        ], [
            'name' => 'październik, listopad, grudzień',
            'number' => 4,
            'begin_at' => '2021.10.01',
            'end_at' => '2021.12.31',
        ], [
            'name' => 'styczeń, luty, marzec',
            'number' => 1,
            'begin_at' => '2022.01.01',
            'end_at' => '2022.03.31',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::PERIODS as $period) {
            Period::factory()
                ->create($period);
        }
    }
}
