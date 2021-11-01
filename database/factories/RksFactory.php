<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Rks;
use Illuminate\Database\Eloquent\Factories\Factory;

class RksFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rks::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'admin_id' => Admin::inRandomOrder()->first()->id ?? null,
        ];
    }
}
