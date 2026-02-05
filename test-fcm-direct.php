<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $order = App\Models\Order::first();
    $admin = App\Models\Admin::first();

    if ($order && $admin) {
        echo "📌 Admin token in DB: " . substr($admin->fcm_token ?? 'NONE', 0, 50) . "...\n";
        echo "📌 Creating notification...\n";

        $notification = new App\Notifications\Admin\AdminOrderCreatedNotification($order);
        $fcmMessage = $notification->toFcm($admin);

        echo "📌 FCM Message type: " . get_class($fcmMessage) . "\n";
        echo "📌 FCM Message data: " . json_encode($fcmMessage->data) . "\n";
        echo "📌 FCM Message notification: " . json_encode($fcmMessage->notification) . "\n";
        echo "📌 FCM Message token: " . $fcmMessage->token . "\n";
        echo "✓ Notification structure looks correct\n";

        // Try sending directly
        echo "\n🔥 Attempting direct Firebase send...\n";
        try {
            $messaging = \Kreait\Laravel\Firebase\Facades\Firebase::project('app')->messaging();
            $result = $messaging->send($fcmMessage->token($admin->fcm_token));
            echo "✅ Direct send result: " . $result . "\n";
        } catch (\Exception $e) {
            echo "❌ Direct send failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✗ No order or admin found\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
