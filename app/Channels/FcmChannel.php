<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\FcmService;

class FcmChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toFcm')) {
            return;
        }

        $fcmToken = $notifiable->fcm_token;
        if (!$fcmToken) {
            return;
        }

        $message = $notification->toFcm($notifiable);

        if ($message) {
            FcmService::sendPush(
                $fcmToken,
                $message['title'],
                $message['body'],
                $message['data'] ?? []
            );
        }
    }
}
