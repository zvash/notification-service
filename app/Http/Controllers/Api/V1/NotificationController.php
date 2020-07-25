<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function send(Request $request, NotificationService $notificationService)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'recipients' => 'required|array',
            'template' => 'required|string',
            'extra_params' => 'array'
        ]);

        if($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }
        $notificationService->notify();
        return response([
            'message' => 'success',
            'errors' => null,
            'status' => true,
            'data' => 'Notifications were successfully queued'
        ], 200);
    }
}
