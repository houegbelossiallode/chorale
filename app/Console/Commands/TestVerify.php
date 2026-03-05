<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Http;
use ReflectionClass;

class TestVerify extends Command
{
    protected $signature = 'test:verify';
    public function handle(SupabaseService $service)
    {
        $reflector = new ReflectionClass($service);
        $urlProp = $reflector->getProperty('url');
        $urlProp->setAccessible(true);
        $url = $urlProp->getValue($service);
        $keyProp = $reflector->getProperty('serviceKey');
        $keyProp->setAccessible(true);
        $key = $keyProp->getValue($service);

        // 1. Generate link
        $genRes = Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key])
            ->post($url . '/auth/v1/admin/generate_link', ['type' => 'recovery', 'email' => 'elyseehouegbelossi@gmail.com', 'redirect_to' => 'http://localhost/reset']);
        $data = $genRes->json();

        $actionLink = $data['action_link'];
        $urlParts = parse_url($actionLink);
        parse_str($urlParts['query'], $query);
        $token = $query['token'];
        $tokenHash = $data['hashed_token'];
        $otp = $data['email_otp'];

        // Test 1: verify with 'token' = query token (hashed token)
        $res1 = Http::withHeaders(['apikey' => $key])->post($url . '/auth/v1/verify', ['type' => 'recovery', 'token' => $token, 'email' => 'elyseehouegbelossi@gmail.com']);

        // Test 2: verify with 'token_hash' = hashed token
        $res2 = Http::withHeaders(['apikey' => $key])->post($url . '/auth/v1/verify', ['type' => 'recovery', 'token_hash' => $tokenHash]);

        // Test 3: verify with 'token' = OTP
        $res3 = Http::withHeaders(['apikey' => $key])->post($url . '/auth/v1/verify', ['type' => 'recovery', 'token' => $otp, 'email' => 'elyseehouegbelossi@gmail.com']);

        $results = [
            'token' => $token,
            'hashed_token' => $tokenHash,
            'otp' => $otp,
            'test1_token' => $res1->json(),
            'test2_token_hash' => $res2->json(),
            'test3_otp' => $res3->json(),
        ];
        file_put_contents(base_path('verify_results.json'), json_encode($results, JSON_PRETTY_PRINT));
        $this->info("Done. See verify_results.json");
    }
}
