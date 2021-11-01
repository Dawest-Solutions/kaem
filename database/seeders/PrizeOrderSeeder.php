<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PrizeOrder;
use App\Services\PosPointsService;
use Illuminate\Database\Seeder;

class PrizeOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::has('pos')
            ->get()
            ->each(function ($user) {

                $pos = $user->pos->first();
                $posPointsService = new PosPointsService($pos);

                if ($posPointsService->availablePoints() > 50) {
                    $points = rand(50, $posPointsService->availablePoints());

                    $user->prizeOrders()->save(
                        PrizeOrder::factory()->make([
                            'user_id' => $user->id,
                            'pos_id' => $pos->id,
                            'points' => $points,
                            'saldo' => $posPointsService->saldoPoints() - $points,
                        ])
                    );
                }

            });
    }
}
