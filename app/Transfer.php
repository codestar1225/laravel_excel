<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $guarded = ['id'];

    public function fromWallet(){
        return $this->belongsTo('App\Wallet', 'from_wallet_id', 'id');
    }

    public function toWallet(){
        return $this->belongsTo('App\Wallet', 'to_wallet_id', 'id');
    }
}
