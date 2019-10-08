<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OverrideBonusSetting extends Model
{
    
    protected $casts = [
        'sponsor' => 'integer',
        'bonus' => 'double',
        'level' => 'integer',
        'invest' => 'double'
    ];
}
