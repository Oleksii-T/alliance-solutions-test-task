<?php

namespace Database\Factories;

use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //dd($this->faker->dateTimeThisMonth($max = 'now', $timezone = null));
        return [
            'name' => $this->faker->name(),
            'position' => $this->faker->jobTitle,
            'wage' => $this->faker->numberBetween($min = 500, $max = 4000),
        ];
    }
}
