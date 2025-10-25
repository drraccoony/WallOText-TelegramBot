<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WallStreak extends Model
{
    protected $fillable = ['user_id', 'last_wall_at'];
    protected $casts = ['last_wall_at' => 'datetime'];
}
