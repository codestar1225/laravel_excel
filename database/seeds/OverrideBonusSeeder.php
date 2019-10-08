<?php

use Illuminate\Database\Seeder;
use App\OverrideBonusSetting;

class OverrideBonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new OverrideBonusSetting();
        $setting->level = 1;
        $setting->sponsor = 1;
        $setting->bonus = 10;
        $setting->invest = 6;
        $setting->save();
        
        $setting = new OverrideBonusSetting();
        $setting->level = 2;
        $setting->sponsor = 2;
        $setting->bonus = 8;
        $setting->invest = 15;
        $setting->save();
        
        $setting = new OverrideBonusSetting();
        $setting->level = 3;
        $setting->sponsor = 3;
        $setting->bonus = 8;
        $setting->invest = 15;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 4;
        $setting->sponsor = 4;
        $setting->bonus = 8;
        $setting->invest = 15;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 5;
        $setting->sponsor = 5;
        $setting->bonus = 8;
        $setting->invest = 15;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 6;
        $setting->sponsor = 6;
        $setting->bonus = 5;
        $setting->invest = 30;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 7;
        $setting->sponsor = 7;
        $setting->bonus = 5;
        $setting->invest = 30;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 8;
        $setting->sponsor = 8;
        $setting->bonus = 5;
        $setting->invest = 30;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 9;
        $setting->sponsor = 9;
        $setting->bonus = 5;
        $setting->invest = 30;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 10;
        $setting->sponsor = 10;
        $setting->bonus = 5;
        $setting->invest = 30;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 11;
        $setting->sponsor = 11;
        $setting->bonus = 1;
        $setting->invest = 60;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 12;
        $setting->sponsor = 12;
        $setting->bonus = 1;
        $setting->invest = 60;
        $setting->save();
        
        $setting = new OverrideBonusSetting();
        $setting->level = 13;
        $setting->sponsor = 13;
        $setting->bonus = 1;
        $setting->invest = 60;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 14;
        $setting->sponsor = 14;
        $setting->bonus = 1;
        $setting->invest = 60;
        $setting->save();

        $setting = new OverrideBonusSetting();
        $setting->level = 15;
        $setting->sponsor = 15;
        $setting->bonus = 1;
        $setting->invest = 60;
        $setting->save();
    }
}
