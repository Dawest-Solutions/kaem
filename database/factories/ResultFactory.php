<?php

namespace Database\Factories;

use App\Models\Period;
use App\Models\Pos;
use App\Models\User;
use App\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Result::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomThreshold = rand(0, 100000);
        $randomTurnover = rand(0, 100000);

        $thresholdBasic = (0.05 * $randomThreshold) + $randomThreshold;
        $thresholdSilver = (0.10 * $randomThreshold) + $randomThreshold;
        $thresholdGold = (0.15 * $randomThreshold) + $randomThreshold;

        $basicPoints = 0.02 * $randomThreshold;
        $silverPoints = 0.03 * $randomThreshold;
        $goldPoints = 0.05 * $randomThreshold;

        $lackingPointsBasic = max($thresholdBasic - $randomTurnover, 0);
        $lackingPointsSilver = max($thresholdSilver - $randomTurnover, 0);
        $lackingPointsGold = max($thresholdGold - $randomTurnover, 0);

        $points = $lackingPointsBasic === 0 ? 0.02 * $randomTurnover : 0;

        $activePoints = 0;
        $inactivePoints = 0;

        $status = Arr::random(['available', 'locked']);
        if ($status === 'locked') {
            $activePoints = $points;
        } else {
            $inactivePoints = $points;
        }


        $pos = Pos::inRandomOrder()->first();
        $period = Period::inRandomOrder()->first();

        $type = Arr::random(['siec','firma']);

        return [
            'number_pos_main' => $pos->number_pos_main,
            'number_pos' => $pos->number_pos,
            'type' => $type,
            'period_id' => $period->id,

            'turnover' => $randomTurnover,

            'threshold_basic' => $thresholdBasic,
            'threshold_silver' => $thresholdSilver,
            'threshold_gold' => $thresholdGold,

            'basic_points' => $basicPoints,
            'silver_points' => $silverPoints,
            'gold_points' => $goldPoints,

            'lacking_points_basic' => $lackingPointsBasic,
            'lacking_points_silver' => $lackingPointsSilver,
            'lacking_points_gold' => $lackingPointsGold,

            'active_points' => $activePoints,
            'inactive_points' => $inactivePoints,

            'status' => $status,
        ];
    }
}
