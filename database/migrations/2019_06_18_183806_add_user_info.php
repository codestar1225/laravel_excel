<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->index()->unique();
            $table->string('id_type')->default("")->nullable();
            $table->string('id_number')->default("")->nullable();
            $table->string('contact')->default("")->nullable();
            $table->smallInteger('status')->default(0);
            $table->smallInteger('kyc_status')->default(0);
            $table->bigInteger('sponsor_id')->unsigned()->nullable();
            $table->string('parents')->index()->default("")->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('sponsor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('id_type');
            $table->dropColumn('id_number');
            $table->dropColumn('contact');
            $table->dropColumn('status');
            $table->dropColumn('kyc_status');
            $table->dropColumn('parents');

            $table->dropForeign(['sponsor_id']);
            $table->dropColumn('sponsor_id');
        });
    }
}
