<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\UsdExchange::create([
            'currency' => 'EUR',
            'buy' => '0',
            'sell' => '0.83',
        ]);

        \App\Models\UsdExchange::create([
            'currency' => 'UAH',
            'buy' => '0',
            'sell' => '28.00',
        ]);
        
        \App\Models\UsdExchange::create([
            'currency' => 'RUB',
            'buy' => '0',
            'sell' => '74.89',
        ]);

        // API key is required
        //\App\Http\Controllers\UsdExchangeController::update();

        \App\Models\User::factory()->create();
        \App\Models\Employer::factory()->count(20)->create();
        \App\Models\Client::factory()->count(2000)->create();
        \App\Models\Payment::factory()->count(6000)->create();
    }
}
