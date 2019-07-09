<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlayerController extends Controller
{
    /**
     * Add a game player.
     *
     * @param  int $gameId, Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function add($gameId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'score' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $game = Game::findOrFail($gameId);

        $player = new Player([
            'name' => $request->get('name'),
            'score' => $request->get('score')
        ]);

        $game->players()->save($player);

        return response()->json([
            'game' => $game,
        ], 201);
    }

}
