<?php

namespace Database\Factories;

use App\Models\Prize;
use App\Models\User;
use App\Models\PrizeOrder;
use App\Services\PosPointsService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class PrizeOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrizeOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'prize_id' => Prize::inRandomOrder()->first()->id,
            'points' => rand(50, 1000),
            'saldo' => rand(0, 10000),
            'status' => Arr::random(['in_progress', 'completed', 'error', 'canceled']),
            'date' => $this->faker->date(),
            'full_name' => $this->faker->name(),
            'phone' => mt_rand(),
            'email' => $this->faker->email(),
            'address' => $this->faker->address(),
            'postal_code' => rand(10,99) . '-'. rand(100,999),
            'city' => $this->faker->city(),
        ];
    }
}
