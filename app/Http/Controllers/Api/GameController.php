<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Game;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Games with sum of its players.
     *
     * @param  Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $games = Game::with('players')->paginate(10);
        return response()->json([
            'games' => $games
        ], 200);
    }

    public function sortByMostPlayedGames()
    {
        // $games = Game::leftJoin('players', 'games.id', '=', 'players.game_id')
        //     ->leftJoin(
        //             DB::raw(
        //                 '(select p.id as player_id, name as player_name, game_id, score as player_score from players p 
        //                 join (select p2.id, max(p2.score) as maxscore
        //                 from players p2 group by p2.id) as q 
        //                 on p.id = q.id
        //                 and p.score = q.maxscore group by q.id order by player_score desc) as plays'), 'games.id', '=', 'plays.game_id')
        //         ->select(
        //             'games.id',
        //             'games.name',
        //             DB::raw('count(players.id) as total_players'),
        //             'plays.player_name',
        //             'plays.player_score'
        //             )
        //         ->groupBy('games.id', 'plays.player_name', 'plays.player_score')
        //         ->get();

        $games = Game::with('top_player')->paginate(10);
        return response()->json([
            'games' => $games
        ], 200);
    }

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
            'message' => 'Game has been saved successfully',
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
            'message' => 'Game has been updated successfully',
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
            'message' => 'Game was deleted.'
        ], 200);
    }

    /**
     * Purge deleted games.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function purgeAll()
    {
        $games = Game::onlyTrashed();

        try {
            $games->forceDelete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }

        return response()->json([
            'message' => 'All trashed games were deleted permanently.'
        ], 200);
    }
}
