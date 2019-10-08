<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupbonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_grouprebate')->default(false);
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->decimal('rebates', 24, 4)->default(0);
            $table->decimal('rebate_rate')->default(0);
            $table->text('rebate_extra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('has_grouprebate');
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn('rebates');
            $table->dropColumn('rebate_rate');
            $table->dropColumn('rebate_extra');
        });
    }
}
