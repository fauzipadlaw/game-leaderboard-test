<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use SoftDeletes;

    protected $table = 'players';
    
    protected $dates = ['deleted_at'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
