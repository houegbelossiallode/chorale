<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Donateur;
use App\Models\Projet;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donations = Donation::with(['donateur', 'projet'])->latest()->paginate(20);
        return view('admin.finance.donations.index', compact('donations'));
    }

    public function create()
    {
        $projets = Projet::all();
        $donateurs = Donateur::all();
        return view('admin.finance.donations.create', compact('projets', 'donateurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'donateur_name' => 'required_without:donateur_id|string|max:255',
            'donateur_email' => 'nullable|email',
            'donateur_id' => 'nullable|exists:donateurs,id',
            'projet_id' => 'required|exists:projets,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'reference_transaction' => 'nullable|string',
        ]);

        $donateurId = $request->donateur_id;

        if (!$donateurId) {
            $donateur = Donateur::firstOrCreate(
                ['email' => $request->donateur_email],
                ['name' => $request->donateur_name]
            );
            $donateurId = $donateur->id;
        }

        $donation = Donation::create([
            'donateur_id' => $donateurId,
            'projet_id' => $request->projet_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'reference_transaction' => $request->reference_transaction,
        ]);

        // Update project 'atteint' amount
        $projet = Projet::find($request->projet_id);
        $projet->increment('atteint', $request->amount);

        return redirect()->route('admin.finance.donations.index')->with('success', 'Donation enregistrée.');
    }

    public function destroy(Donation $donation)
    {
        // Decrement project 'atteint' amount
        $donation->projet->decrement('atteint', $donation->amount);
        $donation->delete();
        return back()->with('success', 'Donation annulée.');
    }
}
