<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'approval_id', 'is_active'
    ];

    public function approval(){
        return $this->belongsTo('App\Approval');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function plan(){
        return $this->belongsTo('App\Plan');
    }
}
