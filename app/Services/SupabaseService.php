<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    public string $url;
    protected string $serviceKey;

    public function __construct()
    {
        $this->url = config('services.supabase.url');
        $this->serviceKey = config('services.supabase.service_key'); // Utiliser la clé Service Role pour outrepasser les RLS
    }

    /**
     * Crée un utilisateur dans Supabase Auth
     */
    public function createUser(string $email, string $password, array $metadata = [])
    {
        $response = Http::withHeaders([
            'apikey' => $this->serviceKey,
            'Authorization' => 'Bearer ' . $this->serviceKey,
        ])->post($this->url . '/auth/v1/admin/users', [
                    'email' => $email,
                    'password' => $password,
                    'user_metadata' => $metadata,
                    'email_confirm' => true, // Activer le compte immédiatement
                ]);

        if ($response->failed()) {
            Log::error('Supabase Create User Error: ' . $response->body());
            return null;
        }

        return $response->json();
    }

    /**
     * Upload un fichier dans un bucket Supabase Storage
     */
    public function uploadFile(string $bucket, string $path, $file)
    {
        // Augmenter les limites pour les gros fichiers (vidéos)
        set_time_limit(600);
        ini_set('max_execution_time', 600);

        $response = Http::withHeaders([
            'apikey' => $this->serviceKey,
            'Authorization' => 'Bearer ' . $this->serviceKey,
        ])
            ->timeout(600) // 10 minutes pour les gros fichiers
            ->withBody(fopen($file->getRealPath(), 'r'), $file->getMimeType())
            ->post($this->url . '/storage/v1/object/' . $bucket . '/' . $path);

        if ($response->failed()) {
            Log::error('Supabase Storage Upload Error: ' . $response->status() . ' - ' . $response->body(), [
                'bucket' => $bucket,
                'path' => $path,
                'mime' => $file->getMimeType()
            ]);
            return null;
        }

        // Retourner l'URL publique du fichier
        return $this->url . '/storage/v1/object/public/' . $bucket . '/' . $path;
    }

    /**
     * Supprime un fichier dans un bucket Supabase Storage
     */
    public function deleteFile(string $bucket, string $path)
    {
        $response = Http::withHeaders([
            'apikey' => $this->serviceKey,
            'Authorization' => 'Bearer ' . $this->serviceKey,
        ])->delete($this->url . '/storage/v1/object/' . $bucket . '/' . $path);

        if ($response->failed()) {
            Log::error('Supabase Storage Delete Error: ' . $response->body());
            return false;
        }

        return true;
    }

    /**
     * Vérifie la validité d'un token Supabase (optionnel si fait en JS)
     */
    public function getUser(string $token)
    {
        $response = Http::withHeaders([
            'apikey' => $this->serviceKey,
            'Authorization' => 'Bearer ' . $token,
        ])->get($this->url . '/auth/v1/user');

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Crée un token de réinitialisation de mot de passe pour l'utilisateur.
     * Retourne le token (string) ou null en cas d'échec.
     */
    public function createResetToken(string $email, int $expiresIn = 3600)
    {
        // Utilise l'endpoint admin pour générer un lien de récupération
        $response = Http::withHeaders([
            'apikey' => $this->serviceKey,
            'Authorization' => 'Bearer ' . $this->serviceKey,
        ])->post($this->url . '/auth/v1/admin/generate_link', [
                    'type' => 'recovery',
                    'email' => $email,
                    'redirect_to' => route('password.reset', ['token' => 'placeholder']) // Le redirect_to est requis mais non utilisé pour l'email
                ]);

        if ($response->failed()) {
            Log::error('Supabase generate_link error: ' . $response->body());
            return null;
        }

        $actionLink = $response->json('action_link');
        $urlParts = parse_url($actionLink);
        parse_str($urlParts['query'] ?? '', $queryParts);

        return $queryParts['token'] ?? null;
    }

    /**
     * Réinitialise le mot de passe d'un utilisateur à l'aide d'un token JWT.
     * Retourne true si la mise à jour a réussi.
     */
    public function resetUserPassword(string $token, string $newPassword)
    {
        // ... (code existant)
    }

    /**
     * Met à jour le mot de passe d'un utilisateur par son email via l'API Admin.
     */
    public function updateUserPasswordByEmail(string $email, string $newPassword)
    {
        // 1. Trouver l'utilisateur par son email avec filtrage
        $listResponse = Http::withHeaders([
            'apikey' => $this->serviceKey,
            'Authorization' => 'Bearer ' . $this->serviceKey,
        ])->get($this->url . '/auth/v1/admin/users', [
                    'email' => $email
                ]);

        if ($listResponse->failed()) {
            Log::error('Supabase List Users Error: ' . $listResponse->body());
            return false;
        }

        $data = $listResponse->json();

        // GoTrue Admin API returns { "users": [...], "aud": "..." } or sometimes just [...]
        $usersList = isset($data['users']) ? $data['users'] : $data;

        // Le filtrage peut retourner un tableau, on prend le premier qui match exactement
        $targetUser = collect($usersList)->firstWhere('email', $email);

        if (!$targetUser) {
            Log::error("Supabase User not found for email: {$email}", ['response_sample' => collect($usersList)->take(1)]);
            return false;
        }

        $userId = $targetUser['id'];
        Log::info("Found Supabase User ID for update", ['email' => $email, 'userId' => $userId]);

        // 2. Mettre à jour le mot de passe
        $updateResponse = Http::withHeaders([
            'apikey' => $this->serviceKey,
            'Authorization' => 'Bearer ' . $this->serviceKey,
        ])->put($this->url . "/auth/v1/admin/users/{$userId}", [
                    'password' => $newPassword,
                ]);

        if ($updateResponse->failed()) {
            Log::error("Supabase Password Update Error for {$email}: " . $updateResponse->body());
            return false;
        }

        return true;
    }
}
