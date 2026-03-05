$service = app(App\Services\SupabaseService::class);
$response = Illuminate\Support\Facades\Http::withHeaders([
'apikey' => config('supabase.service_key'),
'Authorization' => 'Bearer ' . config('supabase.service_key'),
])->post(config('supabase.url') . '/auth/v1/admin/generate_link', [
'type' => 'recovery',
'email' => 'krystoam2@gmail.com',
'redirect_to' => 'http://localhost/reset'
]);
file_put_contents('supaResponse.json', $response->body());
echo "Data dumped to supaResponse.json";