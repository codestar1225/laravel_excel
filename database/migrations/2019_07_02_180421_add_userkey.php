<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserkey extends Migration
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
            $table->dropUnique(['email']);
            $table->string('withdrawkey', 12);
            $table->string('referralkey', 6);
        });

        $users = User::where('withdrawkey', '')->get();
        foreach ($users as $u) {
            $u->withdrawkey = strtoupper(str_random(6));
            $u->referralkey = strtoupper(str_random(6));
            $u->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
            $table->dropColumn('withdrawkey');
            $table->dropColumn('referralkey');
        });
    }
}
