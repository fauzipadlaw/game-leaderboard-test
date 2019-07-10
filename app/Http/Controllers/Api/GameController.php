<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Game;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Collection;
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
        return response()->json($games, 200);
    }

    /**
     * All games without pagination.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $games = Game::all();
        return response()->json($games, 200);
    }

    /**
     * Sort games by the most players.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sortByMostPlayedGames()
    {
        $games = Game::leftJoin('players', 'games.id', '=', 'players.game_id')
            ->select(
                'games.*',
                DB::raw('count(players.id) as total_players')
            )
            ->groupBy('games.id')
            ->orderBy('total_players', 'desc')
            ->get();

        $games->filter(function ($item) {
            $item->top_player = Game::find($item->id)->players()->orderBy('score', 'desc')->first();
        });

        // i tought this should be paginated too :D
        // $games = $this->paginate($games, 10);

        return response()->json($games, 200);
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


    // This function works to paginate a collection
    // This function is not used, it was my misunderstanding...
    protected function paginate($items, $perPage, $setDefaultOption = true, $options = [])
    {
        if ($setDefaultOption) {
            $options = ['path' => request()->url(), 'query' => request()->query()];
        }
        $page = Input::get('page', 1); // Get the current page or default to 1


        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
