<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FcmService
{
    /**
     * FCM v1 Implementation using Service Account JSON.
     */
    public static function sendPush($to, $title, $body, $data = [])
    {
        $accessToken = self::getAccessToken();

        if (!$accessToken) {
            Log::error('FCM: Could not generate access token.');
            return false;
        }

        $authConfig = self::getAuthConfig();
        if (!$authConfig)
            return false;

        $projectId = $authConfig['project_id'];
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $response = Http::withToken($accessToken)
            ->post($url, [
            'message' => [
                'token' => $to,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => array_map('strval', $data), // FCM v1 requires strings in data
            ]
        ]);

        if (!$response->successful()) {
            Log::error('FCM v1 Error: ' . $response->status() . ' - ' . $response->body());
        }
        else {
            Log::info('FCM v1 Success: Notification sent successfully to ' . substr($to, 0, 10) . '...');
        }

        return $response->successful();
    }

    private static function getAuthConfig()
    {
        $path = storage_path('app/firebase-auth.json');
        if (!file_exists($path)) {
            Log::warning('FCM: firebase-auth.json missing at ' . $path);
            return null;
        }
        return json_decode(file_get_contents($path), true);
    }

    /**
     * Manually generate OAuth2 Access Token for FCM v1.
     * To avoid heavy dependencies, we implement a simple JWT flow.
     */
    private static function getAccessToken()
    {
        $config = self::getAuthConfig();
        if (!$config)
            return null;

        $now = time();
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'iss' => $config['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = '';
        openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $config['private_key'], 'SHA256');
        $base64UrlSignature = self::base64UrlEncode($signature);

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        return $response->json('access_token');
    }

    private static function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
