<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    //
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function admin(){
        return $this->belongsTo('App\User', 'admin_id', 'id');
    }

    public function ref(){
        return $this->morphTo();
    }
}
