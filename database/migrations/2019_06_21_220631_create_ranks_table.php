<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->integer('target_id')->index();
            $table->integer('target_count');
            $table->decimal('sales');
            $table->decimal('invest');
            $table->decimal('bonus');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table){
            $table->bigInteger('rank_id')->unsigned()->default(1);
            $table->foreign('rank_id')->references('id')->on('ranks');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropForeign(['rank_id']);
            $table->dropColumn('rank_id');
        });
        Schema::dropIfExists('ranks');
    }
}
