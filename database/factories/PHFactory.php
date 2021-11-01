<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\PH;
use Illuminate\Database\Eloquent\Factories\Factory;

class PHFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PH::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'admin_id' => Admin::inRandomOrder()->first()->id ?? null,
            'name' => $this->faker->name(),
            'first_name' => $this->faker->firstname(),
            'last_name' => $this->faker->lastname(),
        ];
    }
}
