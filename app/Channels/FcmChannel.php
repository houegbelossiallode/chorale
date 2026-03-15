<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\FcmService;
use Illuminate\Support\Facades\Log;

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
        Log::info('FcmChannel: Starting send process for user ' . $notifiable->id);

        if (!method_exists($notification, 'toFcm')) {
            Log::warning('FcmChannel: Notification does not have toFcm method');
            return;
        }

        $fcmToken = $notifiable->fcm_token;
        if (!$fcmToken) {
            Log::warning('FcmChannel: User ' . $notifiable->id . ' has no fcm_token');
            return;
        }

        $message = $notification->toFcm($notifiable);

        if ($message) {
            Log::info('FcmChannel: Sending push to token ' . substr($fcmToken, 0, 10) . '...');
            $result = FcmService::sendPush(
                $fcmToken,
                $message['title'],
                $message['body'],
                $message['data'] ?? []
            );
            Log::info('FcmChannel: Send result', ['success' => $result]);
        }
        else {
            Log::warning('FcmChannel: toFcm returned no message');
        }
    }
}
