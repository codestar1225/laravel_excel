<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'option' => 'company_eth_wallet',
            'val' => '1122334456'
        ]);

        Setting::create([
            'option' => 'felta_ratio',
            'val' => '100'
        ]);

        Setting::create([
            'option' => 'withdrawal_fee',
            'val' => '10'
        ]);
    }
}
