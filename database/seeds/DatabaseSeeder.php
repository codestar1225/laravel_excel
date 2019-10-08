<?php

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
        $this->call(RanksTableSeeder::class);
        $this->call(PlansTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(OverrideBonusSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
