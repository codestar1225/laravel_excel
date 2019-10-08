<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    //
    protected $fillable = [
        'admin_id', 'content', 'is_active'
    ];

    public function admin(){
        return $this->belongsTo('App\User', 'admin_id', 'id');
    }
}
