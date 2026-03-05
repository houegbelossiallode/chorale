<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Http;

class TestSupa extends Command
{
    protected $signature = 'test:supa';
    protected $description = 'Test Supabase generate_link';

    public function handle(SupabaseService $service)
    {
        $reflector = new \ReflectionClass($service);
        $urlProp = $reflector->getProperty('url');
        $urlProp->setAccessible(true);
        $url = $urlProp->getValue($service);

        $keyProp = $reflector->getProperty('serviceKey');
        $keyProp->setAccessible(true);
        $serviceKey = $keyProp->getValue($service);

        $response = Http::withHeaders([
            'apikey' => $serviceKey,
            'Authorization' => 'Bearer ' . $serviceKey,
        ])->post($url . '/auth/v1/admin/generate_link', [
                    'type' => 'recovery',
                    'email' => 'elyseehouegbelossi@gmail.com',
                    'redirect_to' => route('password.reset', ['token' => 'placeholder'])
                ]);

        file_put_contents(base_path('link.json'), json_encode($response->json(), JSON_PRETTY_PRINT));
        $this->info("Dumped to link.json");
    }
}
