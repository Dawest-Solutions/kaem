<?php

namespace Database\Factories;

use App\Models\Prize;
use App\Models\PrizeCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrizeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Prize::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => PrizeCategory::inRandomOrder()->first()->id,
            'model' => $this->faker->text(rand(5, 10)),
            'name' => $this->faker->text(rand(5, 10)),
            'value' => rand(1000,10000),
            'description' => $this->faker->text(rand(25, 50)),
            'visibility' => (bool)rand(0, 1),
            'photo' => 'assets/frontend/img/example.png',
        ];
    }
}
