<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $order = App\Models\Order::first();
    $admin = App\Models\Admin::first();

    echo "📌 Admin token in DB: " . substr($admin->fcm_token, 0, 50) . "...\n";
    echo "📌 Creating notification...\n";

    $notification = new App\Notifications\Admin\AdminOrderCreatedNotification($order);
    $fcmMessage = $notification->toFcm($admin);

    echo "📌 Message class: " . get_class($fcmMessage) . "\n";
    echo "📌 Message is NotificationChannels\\Fcm\\FcmMessage: " . ($fcmMessage instanceof \NotificationChannels\Fcm\FcmMessage ? 'YES' : 'NO') . "\n";

    // This is what the vendor FcmChannel does
    echo "\n🔥 Attempting vendor FcmChannel approach...\n";
    $messaging = \Kreait\Laravel\Firebase\Facades\Firebase::project('app')->messaging();

    // The vendor channel calls sendMulticast on the FcmMessage with token array
    $tokens = [$admin->fcm_token];
    echo "📌 Sending to " . count($tokens) . " token(s)...\n";

    $report = $messaging->sendMulticast($fcmMessage, $tokens);

    echo "✅ Multicast send completed!\n";
    echo "📌 Success: " . $report->successes()->count() . "\n";
    echo "📌 Failures: " . $report->failures()->count() . "\n";

    if ($report->failures()->count() > 0) {
        foreach ($report->failures() as $failure) {
            echo "  ❌ " . $failure->error()->getMessage() . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📌 Type: " . get_class($e) . "\n";
}
