<?php

use Illuminate\Database\Seeder;
use App\Rank;

class RanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Rank();
        $setting->label = 'VIP 0';
        $setting->target_id = 0;
        $setting->target_count = 0;
        $setting->sales = 0;
        $setting->bonus = 0;
        $setting->invest = 0;
        $setting->save();

        $setting = new Rank();
        $setting->label = 'VIP 1';
        $setting->target_id = 1;
        $setting->target_count = 15;
        $setting->sales = 300;
        $setting->bonus = 4;
        $setting->invest = 60;
        $setting->save();

        $setting = new Rank();
        $setting->label = 'VIP 2';
        $setting->target_id = 2;
        $setting->target_count = 1;
        $setting->sales = 600;
        $setting->bonus = 5;
        $setting->invest = 90;
        $setting->save();

        $setting = new Rank();
        $setting->label = 'VIP 3';
        $setting->target_id = 3;
        $setting->target_count = 2;
        $setting->sales = 1200;
        $setting->bonus = 6;
        $setting->invest = 150;
        $setting->save();

        $setting = new Rank();
        $setting->label = 'VIP 4';
        $setting->target_id = 4;
        $setting->target_count = 3;
        $setting->sales = 3000;
        $setting->bonus = 7;
        $setting->invest = 150;
        $setting->save();

        $setting = new Rank();
        $setting->label = 'VIP 5';
        $setting->target_id = 5;
        $setting->target_count = 4;
        $setting->sales = 10000;
        $setting->bonus = 10;
        $setting->invest = 150;
        $setting->save();
    }
}
