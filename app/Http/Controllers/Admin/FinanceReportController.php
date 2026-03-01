<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionFinanciere;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceReportController extends Controller
{
    /**
     * Export transactions to CSV.
     */
    public function exportCSV(Request $request)
    {
        $fileName = 'transactions_' . date('Y-m-d') . '.csv';
        $transactions = TransactionFinanciere::with('categorie')->latest()->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            // Support for Excel (UTF-8 BOM)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['ID', 'Date', 'Description', 'Type', 'Catégorie', 'Montant (FCFA)', 'Référence']);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->id,
                    $t->created_at->format('d/m/Y H:i'),
                    $t->description,
                    ucfirst($t->type),
                    $t->categorie->libelle,
                    $t->montant,
                    $t->reference
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate a monthly PDF report (Printable view).
     */
    public function reportPDF(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $transactions = TransactionFinanciere::with('categorie')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        $totalRecettes = $transactions->where('type', 'recette')->sum('montant');
        $totalDepenses = $transactions->where('type', 'depense')->sum('montant');

        $statsParCategorie = $transactions->groupBy('categorie_id')->map(function ($items) {
            return [
                'libelle' => $items->first()->categorie->libelle,
                'type' => $items->first()->type,
                'total' => $items->sum('montant')
            ];
        });

        return view('admin.finance.reports.monthly_pdf', [
            'transactions' => $transactions,
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'solde' => $totalRecettes - $totalDepenses,
            'statsParCategorie' => $statsParCategorie,
            'monthName' => \Carbon\Carbon::create()->month($month)->translatedFormat('F'),
            'year' => $year
        ]);
    }
}
