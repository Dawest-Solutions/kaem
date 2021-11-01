<?php

namespace Database\Factories;

use App\Models\Pos;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    protected static int $id = 1;

    /**
     * @var int
     */
    protected static int $number = 0;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'pos_id' =>  Pos::inRandomOrder()->first()->id,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'pesel' => $this->faker->pesel(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('Test1234@'), // password
            'remember_token' => Str::random(10),
            'register_code' => uniqid(self::$number++),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function registered(){
        return $this->state(function (array $attributes) {
            return [
                'register_code' => null,
            ];
        });
    }
}
