<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    /**
     * Add a game.
     *
     * @param  Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $game = Game::create([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'game' => $game
        ], 201);
    }


    /**
     * Update a game.
     *
     * @param  int $id, Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $game = Game::findOrFail($id);

        $game->update([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'game' => $game
        ], 201);
    }

    /**
     * Soft delete a game.
     *
     * @param  int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $game = Game::findOrFail($id);

        try {
            $game->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }

        return response()->json([
            'message' => 'Game deleted successfully.'
        ], 200);
    }

    /**
     * Purge deleted games.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function purgeAll()
    {
        $games = Game::onlyTrashed()->get();

        try {
            $games->forceDelete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }

        return response()->json([
            'message' => 'All trashed games deleted successfully.'
        ], 200);
    }
    
}
