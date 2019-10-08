<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseDecimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();
        DB::statement('ALTER TABLE `wallets` CHANGE `balance` `balance` decimal(24,4) NOT NULL DEFAULT 0;');
        DB::statement('ALTER TABLE `payouts` CHANGE `interest` `interest` decimal(24,4) NOT NULL DEFAULT 0;');
        DB::statement('ALTER TABLE `payouts` CHANGE `override` `override` decimal(24,4) NOT NULL DEFAULT 0;');
        DB::statement('ALTER TABLE `payouts` CHANGE `ranking` `ranking` decimal(24,4) NOT NULL DEFAULT 0;');
        DB::statement('ALTER TABLE `transactions` CHANGE `amount` `amount` decimal(24,4) NOT NULL DEFAULT 0;');
        DB::statement('ALTER TABLE `transfers` CHANGE `from_amount` `from_amount` decimal(24,4) NOT NULL DEFAULT 0;');
        DB::statement('ALTER TABLE `transfers` CHANGE `to_amount` `to_amount` decimal(24,4) NOT NULL DEFAULT 0;');
        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
