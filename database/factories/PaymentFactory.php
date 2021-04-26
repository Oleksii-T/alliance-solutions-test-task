<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $curencies = ['USD', 'EUR', 'UAH', 'RUB'];
        $cur = $curencies[array_rand($curencies)];
        //generate fair pauments up to currency. hard coded cause it is just a seed.
        switch ($cur) {
            case 'USD':
                $amount = $this->faker->numberBetween($min = 100, $max = 8000);
                break;
            case 'EUR':
                $amount = $this->faker->numberBetween($min = 82, $max = 6619);
                break;
            case 'UAH':
                $amount = $this->faker->numberBetween($min = 2785, $max = 222827);
                break;
            case 'RUB':
                $amount = $this->faker->numberBetween($min = 7494, $max = 599555);
                break;
            default:
                $amount = $this->faker->numberBetween($min = 100, $max = 8000);
                break;
        }

        return [
            'employer_id' => rand(1,20),
            'client_id' => rand(1,2000),
            'amount' => $amount,
            'currency' => $cur,
            'verified_at' => $this->faker->dateTimeBetween($startDate = '-6 month', $endDate = 'now', $timezone = null)
        ];
    }
}
