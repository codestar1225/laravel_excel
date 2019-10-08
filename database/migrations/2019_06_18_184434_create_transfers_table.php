<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('from_wallet_id')->unsigned();
            $table->bigInteger('to_wallet_id')->unsigned();
            $table->decimal('from_amount', 24, 4)->default(0);
            $table->decimal('to_amount', 24, 4)->default(0);
            $table->decimal('fee')->default(0);
            $table->timestamps();
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->foreign('from_wallet_id')->references('id')->on('wallets');
            $table->foreign('to_wallet_id')->references('id')->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
