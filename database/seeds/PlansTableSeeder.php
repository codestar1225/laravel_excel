<?php

use Illuminate\Database\Seeder;
use App\Plan;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'label' => '6 ETH',
            'user_comm' => 40,
            'company_comm' => 60,
            'price_type' => 'ETH',
            'price' => 6
        ]);
        Plan::create([
            'label' => '15 ETH',
            'user_comm' => 40,
            'company_comm' => 60,
            'price_type' => 'ETH',
            'price' => 15
        ]);
        Plan::create([
            'label' => '30 ETH',
            'user_comm' => 50,
            'company_comm' => 50,
            'price_type' => 'ETH',
            'price' => 30
        ]);
        Plan::create([
            'label' => '60 ETH',
            'user_comm' => 60,
            'company_comm' => 40,
            'price_type' => 'ETH',
            'price' => 60
        ]);
        Plan::create([
            'label' => '90 ETH',
            'user_comm' => 70,
            'company_comm' => 30,
            'price_type' => 'ETH',
            'price' => 90
        ]);
        Plan::create([
            'label' => '150 ETH',
            'user_comm' => 80,
            'company_comm' => 20,
            'price_type' => 'ETH',
            'price' => 150
        ]);
    }
}
