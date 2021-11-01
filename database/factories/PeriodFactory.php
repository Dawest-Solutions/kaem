<?php

namespace Database\Factories;

use App\Models\Period;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PeriodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Period::class;

    private static int $number = 1;

    private static ?Carbon $date = null;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        self::$date = self::$date ? self::$date->addMonth(1) : now();

        $dateStart = Carbon::create(self::$date)->startOfMonth();
        self::$date = $dateEnd = Carbon::create(self::$date)->addMonth(2,3)->endOfMonth();

        $monthNames  = collect(CarbonPeriod::create($dateStart, '1 month', $dateEnd))
            ->map(fn($date) => $date->monthName)
            ->join(',');

        return [
            'name' => $monthNames,
            'number' => self::$number++,
            'begin_at' => $dateStart,
            'end_at' => $dateEnd,
        ];
    }
}
