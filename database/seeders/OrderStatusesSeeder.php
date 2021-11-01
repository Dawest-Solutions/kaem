<?php

namespace Database\Seeders;

use App\Models\OrderStatuses;
use Illuminate\Database\Seeder;

class OrderStatusesSeeder extends Seeder
{
    public const orderStatuses = [
        'Nowe',
        'Zamówione u dostawcy',
        'Wysłane do klienta',
        'Zrealizowane',
        'Anulowane',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::orderStatuses as $status) {
            OrderStatuses::updateOrCreate([
                'name' => $status,
            ]);
        }
    }
}
