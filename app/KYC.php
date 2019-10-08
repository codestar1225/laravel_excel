<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KYC extends Model
{
    //
    protected $fillable = [
        'user_id', 'approval_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function approval(){
        return $this->belongsTo('App\Approval');
    }
}
