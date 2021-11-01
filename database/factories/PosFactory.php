<?php

namespace Database\Factories;

use App\Models\Ph;
use App\Models\Pos;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class PosFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pos::class;

    /**
     * @var null
     */
    protected static ?int $oldNumberPosMain = null;

    /**
     * @var null
     */
    protected static ?int $numberPosMain = null;

    /**
     * @var int|null
     */
    protected static ?int $numberPos = null;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (is_null(self::$oldNumberPosMain)){
            self::$oldNumberPosMain = rand(10000, 100000);
        }
        if (is_null(self::$numberPosMain)){
            self::$numberPosMain = rand(10000, 100000);
        }

        self::$numberPos = rand(10000, 100000);

        if ((bool)rand(0, 1)) {
            self::$numberPosMain = rand(10000, 100000);
        }

        if (self::$oldNumberPosMain != self::$numberPosMain) {
            self::$oldNumberPosMain = self::$numberPos = self::$numberPosMain;
        }

        $type = Arr::random(['siec','firma']);

        return [
            'ph_id' => Ph::inRandomOrder()->first()->id ?? null,
            'type' => $type,
            'number_pos_main' => self::$numberPosMain,
            'number_pos' => self::$numberPos,
            'company_name' => $this->faker->company(),
        ];
    }
}
