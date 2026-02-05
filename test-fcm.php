<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $order = App\Models\Order::first();
    $admin = App\Models\Admin::first();

    if ($order && $admin) {
        $admin->notify(new App\Notifications\Admin\AdminOrderCreatedNotification($order));
        echo "✓ FCM notification queued for admin ID: {$admin->id}\n";
    } else {
        echo "✗ No order or admin found\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
