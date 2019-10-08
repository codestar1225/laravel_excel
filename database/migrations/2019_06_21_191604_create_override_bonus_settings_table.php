<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverrideBonusSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('override_bonus_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('level');
            $table->integer('sponsor');
            $table->decimal('bonus');
            $table->decimal('invest');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('override_bonus_settings');
    }
}
