<?php

namespace App\Services;

use App\Models\TransactionFinanciere;
use App\Models\Caisse;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Enregistrer une transaction et mettre à jour le solde de la caisse.
     */
    public function recordTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Récupérer ou créer la caisse par défaut (Caisse Principale)
            $caisse = Caisse::firstOrCreate(
                ['nom' => 'Caisse Principale'],
                ['solde' => 0]
            );

            // 2. Créer la transaction
            $transaction = TransactionFinanciere::create([
                'description' => $data['description'],
                'type' => $data['type'],
                'categorie_id' => $data['categorie_id'],
                'montant' => $data['montant'],
                'reference' => $data['reference'] ?? null,
                'caisse_id' => $caisse->id,
            ]);

            // 3. Mettre à jour le solde de la caisse
            if ($data['type'] === 'recette') {
                $caisse->increment('solde', $data['montant']);
            } else {
                $caisse->decrement('solde', $data['montant']);
            }

            return $transaction;
        });
    }

    /**
     * Annuler une transaction et restaurer le solde.
     */
    public function deleteTransaction(TransactionFinanciere $transaction)
    {
        return DB::transaction(function () use ($transaction) {
            $caisse = $transaction->caisse;

            if ($caisse) {
                if ($transaction->type === 'recette') {
                    $caisse->decrement('solde', $transaction->montant);
                } else {
                    $caisse->increment('solde', $transaction->montant);
                }
            }

            return $transaction->delete();
        });
    }
}
