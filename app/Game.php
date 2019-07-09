<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Game extends Model
{
    use SoftDeletes;

    protected $table = 'games';

    protected $dates = ['deleted_at'];

    protected $appends = ['total_players'];

    protected $fillable = ['name'];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function getTotalPlayersAttribute()
    {
        return $this->players()->count();
    }

    public function top_player()
    {
        return $this->hasOne(Player::class, 'game_id', 'id')->orderBy('score', 'desc')->limit(1);
    }

}
