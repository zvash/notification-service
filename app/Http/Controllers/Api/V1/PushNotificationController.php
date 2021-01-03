<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Berkayk\OneSignal\OneSignalClient;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    public function test(OneSignalClient $client)
    {
        $client->testCredentials();
    }
}
