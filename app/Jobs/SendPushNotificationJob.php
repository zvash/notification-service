<?php

namespace App\Jobs;

use App\Player;
use App\PushNotification;
use Berkayk\OneSignal\OneSignalClient;
use OneSignal;

class SendPushNotificationJob extends Job
{
    /**
     * @var array $messageByUserId
     */
    protected $messageByUserId;

    /**
     * SendPushNotificationJob constructor.
     * @param array $messageByUserId
     */
    public function __construct(array $messageByUserId)
    {
        $this->messageByUserId = $messageByUserId;
    }

    /**
     *
     */
    public function handle()
    {
        foreach ($this->messageByUserId as $userId => $message) {
            $pushNotification = $this->save($userId, $message);
            $this->send($pushNotification);
        }
    }

    /**
     * @param int $userId
     * @param string $message
     * @return PushNotification
     */
    private function save(int $userId, string $message)
    {
        return PushNotification::create([
            'user_id' => $userId,
            'message' => $message
        ]);
    }

    /**
     * @param PushNotification $pushNotification
     */
    private function send(PushNotification $pushNotification)
    {
        $playerIds = Player::where('user_id', $pushNotification->user_id)
            ->where('enabled', true)
            ->pluck('player_id')
            ->toArray();
        foreach ($playerIds as $playerId) {
            OneSignal::sendNotificationToUser(
                $pushNotification->message,
                $playerId,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );
        }
    }

}