<?php

namespace App\Repositories;


use App\Player;

class PlayerRepository
{
    /**
     * @param int $userId
     * @param string $playerId
     * @param string $platform
     * @param null|string $deviceToken
     * @return mixed
     */
    public function registerPlayer(int $userId, string $playerId, string $platform, ?string $deviceToken = null)
    {
        if ($deviceToken) {
            Player::where('device_token')
                ->orWhere('player_id', $playerId)
                ->delete();
        }

        $player = Player::create([
            'user_id' => $userId,
            'player_id' => $playerId,
            'platform' => $platform,
            'device_token' => $deviceToken
        ]);
        $player->token = make_random_hash('player_token');
        $player->save();
        $player->refresh();
        return $player;
    }

    /**
     * @param int $userId
     * @param null|string $playerId
     * @param null|string $platform
     * @param null|string $playerToken
     * @param null|string $deviceToken
     */
    public function removePlayer(int $userId, ?string $playerId, ?string $platform = null, ?string $playerToken = null, ?string $deviceToken = null)
    {
        $player = Player::query();
        $player = $player->where('user_id', $userId);
        if ($playerToken) {
            $player = $player->where('token', $playerToken);
        }
        if ($deviceToken) {
            $player = $player->where('device_token', $deviceToken);
        }
        if ($platform) {
            $player = $player->where('platform', $platform);
        }
        if ($playerId) {
            $player = $player->where('player_id', $playerId);
        }
        $player->delete();
    }
}