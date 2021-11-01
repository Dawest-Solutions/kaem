<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(rand(3,5)),
            'description' => $this->faker->text(500),
            'description_short' => $this->faker->text(150),
            'published' => (bool)rand(0,1),
            'image' => 'assets/frontend/img/example.png',
        ];
    }
}
