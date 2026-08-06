<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class TestFcm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:fcm {token} {title} {body}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test FCM message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = $this->argument('token');
        $title = $this->argument('title');
        $body = $this->argument('body');

        $messaging = app('firebase.messaging');

        $notification = Notification::create($title, $body);

        $message = CloudMessage::fromArray([
            'token' => $token,
            'notification' => $notification,
        ]);

        $result = $messaging->send($message);

        $this->info('Message sent: ' . $result);
    }
}
