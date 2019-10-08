<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'double',
        'user_comm' => 'double',
        'company_comm' => 'double',
        'bonus' => 'double',
    ];
}
