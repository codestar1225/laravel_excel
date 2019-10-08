<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RankBonusSetting extends Model
{
    protected $casts = [
        'target_count' => 'integer',
        'bonus' => 'double',
        'sales' => 'double',
        'invest' => 'double'
    ];
}
