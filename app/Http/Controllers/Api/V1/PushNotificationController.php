<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotificationJob;
use App\PushNotification;
use App\Traits\ResponseMaker;
use Berkayk\OneSignal\OneSignalClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    use ResponseMaker;

    public function test(OneSignalClient $client)
    {
        $client->testCredentials();
    }

    /**
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function all(Request $request, int $userId)
    {
        if ($userId) {
            $page = $request->exists('page') ? $request->get('page') : 1;
            $pushes = PushNotification::where('user_id', $userId)
                ->orderBy('updated_at', 'DESC')
                ->paginate(10, ['*'], 'page', $page);
            return $this->success($pushes);
        }
        return $this->failMessage('Content not found', 404);
    }

    /**
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function sendMessage(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|filled|string',
        ]);

        if ($validator->fails()) {
            return $this->failValidation($validator->errors());
        }
        $this->dispatch(new SendPushNotificationJob([$userId => $request->get('message')]));
        return $this->success('queued');
    }
}
