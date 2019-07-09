<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use SoftDeletes;

    protected $table = 'players';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'score',
        'game_id'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public static function getAllDetailPlayers()
    {
        return Player::join('games', 'players.game_id', '=', 'games.id')
            ->select('players.name as player_name', 'players.score', 'games.name as game')
            ->paginate(10);
    }
}
