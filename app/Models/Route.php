<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = ['start', 'goal', 'start_time', 'via', 'route_data'];
    
    protected $casts = [
        'start_time' => 'datetime',
        'via' => 'array',
        'route_data' => 'array',
    ];
    
    //リレーションシップ
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
