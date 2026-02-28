<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Donateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class DonationController extends Controller
{
    public function index()
    {
        return view('donation');
    }

    public function success(Request $request)
    {
        // Kkiapay redirige avec transaction_id en paramètre
        $transactionId = $request->query('transaction_id');

        if (!$transactionId) {
            return redirect()->route('donation')->with('error', 'La transaction a échoué ou a été annulée.');
        }

        try {
            $baseUrl = config('services.kkiapay.mode') === 'production'
                ? 'https://api.kkiapay.me'
                : 'https://api-sandbox.kkiapay.me';

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-SECRET-KEY' => config('services.kkiapay.secret'),
                'X-API-KEY' => config('services.kkiapay.public_key'),
                'X-PRIVATE-KEY' => config('services.kkiapay.private_key'),
            ])->post($baseUrl . '/api/v1/transactions/status', [
                        'transactionId' => $transactionId,
                    ]);

            if (!$response->successful()) {
                Log::error('KKIAPAY API ERROR', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return redirect()->route('donation')->with('error', 'Erreur de communication avec Kkiapay.');
            }

            $transaction = $response->json();
            Log::info("Kkiapay Transaction Data:", $transaction);

            if (($transaction['status'] ?? '') !== 'SUCCESS') {
                return redirect()->route('donation')->with('error', 'Le paiement n\'a pas pu être validé par Kkiapay.');
            }

            // Récupération sécurisée des données du client
            $clientEmail = $transaction['client']['email'] ?? 'anonyme@example.com';
            $clientName = trim(($transaction['client']['first_name'] ?? '') . ' ' . ($transaction['client']['last_name'] ?? ''));
            $clientPhone = $transaction['client']['phone'] ?? null;
            $amount = $transaction['amount'] ?? 0;

            // 1. Créer ou mettre à jour le donateur
            $donateur = Donateur::updateOrCreate(
                ['email' => $clientEmail],
                [
                    'name' => $clientName ?: 'Donateur Anonyme',
                    'phone' => $clientPhone
                ]
            );

            // 2. Enregistrer le don
            Donation::create([
                'donateur_id' => $donateur->id,
                'amount' => $amount,
                'payment_method' => 'KKIAPAY',
                'reference_transaction' => $transactionId,
            ]);

            return view('donation-success', [
                'amount' => $amount,
                'transactionId' => $transactionId
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur critique enregistrement don: " . $e->getMessage());
            return redirect()->route('donation')->with('error', 'Une erreur est survenue lors du traitement de votre don.');
        }
    }
}
