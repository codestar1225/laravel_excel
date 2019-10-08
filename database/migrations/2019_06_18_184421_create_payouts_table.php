<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('admin_id')->unsigned();
            $table->decimal('rate')->default(0);
            $table->text('extra')->nullable();
            $table->decimal('interest',  24, 4)->default(0);
            $table->decimal('override', 24, 4)->default(0);
            $table->decimal('ranking', 24, 4)->default(0);
            $table->timestamps();
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payouts');
    }
}
