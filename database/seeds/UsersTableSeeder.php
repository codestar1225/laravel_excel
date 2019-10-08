<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Wallet;
use App\Events\UserCreated;
use App\Transaction;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'email' => 'admin@felta.com',
            'name' => 'Admin',
            'username' => 'admin',
            'role' => 'admin',
            'status' => 1,
            'kyc_status' => 1,
            'password' => bcrypt('admin')
        ]);
        event(new UserCreated($user));

        $user = User::create([
            'email' => 'sys@felta.com',
            'name' => 'System',
            'username' => 'sys',
            'role' => 'sys',
            'status' => 1,
            'kyc_status' => 1,
            'password' => bcrypt('axYpT3qk/k1Md')
        ]);
        event(new UserCreated($user));
        
        $user = User::create([
            'email' => 'company@felta.com',
            'name' => 'Company',
            'username' => 'company',
            'role' => 'member',
            'status' => 1,
            'kyc_status' => 1,
            'password' => bcrypt('company')
        ]);
        event(new UserCreated($user));

        $wallet = Wallet::where('user_id', $user->id)->where('type', 'ETH')->first();
        $tx = new Transaction();
        $tx->type = 'INIT';
        $tx->wallet()->associate($wallet);
        $tx->ref()->associate($user);
        $tx->amount = 1000;
        $tx->save();

    }
}
