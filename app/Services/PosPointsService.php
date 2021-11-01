<?php

namespace App\Services;

use App\Models\Pos;
use App\Models\Prize;
use App\Models\PrizeOrder;
use App\Models\Result;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PosPointsService
{
    private Pos $pos;
    private Collection $results;
    private Collection $prizeOrders;

    /**
     * @param Pos $pos
     */
    public function __construct(Pos $pos)
    {
        $this->pos = $pos->load('results.period', 'prizeOrders');
        $this->results = ($pos->isMain && $pos->isNetwork)
            ? $this->pos->results()->where('type', 'siec')->with('period')->get()
            : $pos->results
            ?? collect();
        $this->prizeOrders = $pos->prizeOrders ?? collect();
    }

    /**
     * @return int
     */
    public function availablePoints(): int
    {
        return $this->results->sum('active_points') - $this->spentPoints();
    }


    /**
     * @return int
     */
    public function inactivePoints(): int
    {
        return $this->results->sum('inactive_points');
    }

    /**
     * @return int
     */
    public function earnedPoints(): int
    {
        return $this->pos->results->sum(function (Result $result) {
            return $result->active_points;
        });
    }

    /**
     * @return int
     */
    public function spentPoints(): int
    {
        return $this
            ->prizeOrders
            ->where('status_id', '!=', '5') // except for canceled orders
            ->sum('value');
    }

    public function saldoPoints(): int
    {
        return $this->earnedPoints() - $this->spentPoints();
    }

    /**
     * @param Prize $prize
     * @return int
     */
    public function lackingPointsToPurchased(Prize $prize): int
    {
        return max($prize->value - $this->availablePoints(), 0);
    }

    /**
     * @return Collection
     */
    public function historyPoints(): Collection
    {
        $resultMapping = $this->results
            ->where('active_points', '>', 0)
            ->map(function ($result) {
                return [
                    'id' => $result->id,
                    'date' => Carbon::create($result->updated_at)->format('Y-m-d') ?? null,
                    'points' => "{$result->active_points}",
                    'description' => 'Kwartał ' . $result->period->number,
                ];
            });

        $prizeOrderMapping = $this->prizeOrders->map(function (PrizeOrder $prizeOrders) {
            return [
                'id' => $prizeOrders->id,
                'date' => Carbon::create($prizeOrders->order_date)->format('Y-m-d') ?? null,
                'points' => -$prizeOrders->value,
                'description' => 'Zamówienie nagrody',
            ];
        });

        return collect()
            ->merge($resultMapping)
            ->merge($prizeOrderMapping)
            ->sortByDesc('data');
    }
}
