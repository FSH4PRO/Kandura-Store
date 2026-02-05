<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$admin = App\Models\Admin::first();
$token = $admin->fcm_token;

echo "🔍 Testing token: " . substr($token, 0, 40) . "...\n\n";

try {
    $messaging = Kreait\Laravel\Firebase\Facades\Firebase::project('app')->messaging();

    $message = Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $token)
        ->withNotification(
            Kreait\Firebase\Messaging\Notification::create('🔥 Test Notification', 'If you see this, FCM works!')
        );

    echo "📤 Sending to Firebase...\n";
    $result = $messaging->send($message);
    echo "✅ Message sent successfully!\n";
    echo "📌 Firebase response: " . $result . "\n";
} catch (\Throwable $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📌 Error code: " . $e->getCode() . "\n";
}
