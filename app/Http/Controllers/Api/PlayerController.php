<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Player;

class PlayerController extends Controller
{
    /**
     * Players with game and score.
     *
     * @param  Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'players' => Player::getAllDetailPlayers()
        ], 200);
    }

    /**
     * Add a game player.
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'score' => 'required|integer',
            'game_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $player = Player::create([
            'name' => $request->get('name'),
            'score' => $request->get('score'),
            'game_id' => $request->get('game_id')
        ]);

        return response()->json([
            'message' => 'Player has been saved successfully.',
            'player' => $player,
        ], 201);
    }

    /**
     * Update a game player.
     *
     * @param int $id, Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'game_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $player = Player::findOrFail($id);

        $player->update([
            'name' => $request->get('name'),
            'game_id' => $request->get('game_id')
        ]);

        return response()->json([
            'message' => 'Player has been updated successfully.',
            'player' => $player,
        ], 201);
    }

    /**
     * Delete a game player.
     *
     * @param int $id, Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, Request $request)
    {
        $player = Player::findOrFail($id);

        try {
            $player->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'Player was deleted.',
        ], 201);
    }

    /**
     * Pruge deleted game players.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function purgeAll()
    {
        $players = Player::onlyTrashed();

        try {
            $players->forceDelete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'Players were deleted permanently.',
        ], 201);
    }

}
