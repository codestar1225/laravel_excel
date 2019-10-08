<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    public function wallet(){
        return $this->belongsTo('App\Wallet');
    }

    public function ref(){
        return $this->morphTo();
    }

    protected $dispatchesEvents = [
        'created' => \App\Events\TransactionCreated::class,
        'deleted' => \App\Events\TransactionDeleted::class,
    ];
}
