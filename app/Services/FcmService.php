<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * NOTE: This is a legacy FCM implementation using the Server Key.
     * FCM HTTP v1 (OAuth2) is recommended, but requires more setup.
     */
    public static function sendPush($to, $title, $body, $data = [])
    {
        $serverKey = env('FCM_SERVER_KEY');

        if (!$serverKey) {
            \Log::warning('FCM_SERVER_KEY not set.');
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $to,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
            'data' => $data,
        ]);

        return $response->successful();
    }
}
