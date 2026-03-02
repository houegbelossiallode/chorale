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
    public function exportExcel(Request $request)
    {
        $month = (int) $request->get('month', date('m'));
        $year = (int) $request->get('year', date('Y'));

        $transactions = TransactionFinanciere::with('categorie')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();

        $totalRecettes = $transactions->where('type', 'recette')->sum('montant');
        $totalDepenses = $transactions->where('type', 'depense')->sum('montant');

        $monthName = \Carbon\Carbon::create()->month($month)->translatedFormat('F');
        $fileName = "Rapport_Financier_{$monthName}_{$year}.xls";

        $html = view('admin.finance.reports.export_excel', [
            'transactions' => $transactions,
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'monthName' => $monthName,
            'year' => $year
        ])->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
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
            'monthName' => \Carbon\Carbon::create()->month((int) $month)->translatedFormat('F'),
            'year' => $year
        ]);
    }
}
