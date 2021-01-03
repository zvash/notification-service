<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\PlayerRepository;
use App\Traits\ResponseMaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    use ResponseMaker;

    /**
     * @param Request $request
     * @param PlayerRepository $playerRepository
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function registerPlayerId(Request $request, PlayerRepository $playerRepository)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|string',
            'platform' => 'required|string|in:web,android,ios',
            'user_id' => 'required|int|min:1',
            'device_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->failValidation($validator->errors());
        }

        $userId = $request->get('user_id');
        $playerId = $request->get('player_id');
        $platform = $request->get('platform');
        $deviceToken = $request->exists('device_token') ? $request->get('device_token') : null;
        $player = $playerRepository->registerPlayer($userId, $playerId, $platform, $deviceToken);
        return $this->success(['player_token' => $player->token]);

    }

    /**
     * @param Request $request
     * @param PlayerRepository $playerRepository
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function removePlayer(Request $request, PlayerRepository $playerRepository)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|int|min:1',
            'player_id' => 'nullable|string',
            'platform' => 'nullable|string|in:web,android,ios',
            'device_token' => 'nullable|string',
            'player_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->failValidation($validator->errors());
        }
        $userId = $request->get('user_id');
        $playerId = $request->exists('player_id') ? $request->get('player_id') : null;
        $platform = $request->exists('platform') ? $request->get('platform') : null;
        $deviceToken = $request->exists('device_token') ? $request->get('device_token') : null;
        $playerToken = $request->exists('player_token') ? $request->get('player_token') : null;
        $playerRepository->removePlayer($userId, $playerId, $platform, $playerToken, $deviceToken);
        return $this->success('done');
    }
}
