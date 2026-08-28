<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Factory;

class AdminFcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'fcm_token' => ['required', 'string'],
        ]);

        $admin = auth('admin')->user();
        $admin->update(['fcm_token' => $data['fcm_token']]);

        return response()->json(['ok' => true]);
    }

    public function sendTest(Request $request)
    {
        $data = $request->validate([
            'fcm_token' => ['required', 'string'],
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
        ]);

        $messaging = app('firebase.messaging');

        $notification = Notification::create($data['title'], $data['body']);

        $message = CloudMessage::fromArray([
            'token' => $data['fcm_token'],
            'notification' => $notification,
            'data' => []
        ]);

        $messaging->send($message);

        return response()->json([
            'message' => true
        ]);
    }
}
