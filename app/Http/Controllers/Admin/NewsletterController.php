<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\NewsletterSubscription;
use App\Mail\NewsletterMail;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscription::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.newsletter.index', compact('subscribers'));
    }

    public function history()
    {
        $histories = NewsletterHistory::with('sender')->latest()->paginate(15);
        return view('admin.newsletter.history', compact('histories'));
    }

    public function create()
    {
        $activeSubscribersCount = NewsletterSubscription::where('is_active', DB::raw('true'))->count();
        return view('admin.newsletter.create', compact('activeSubscribersCount'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $subscribers = NewsletterSubscription::where('is_active', DB::raw('true'))->get();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'Aucun abonné actif trouvé.');
        }

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterMail($request->subject, $request->content));
        }

        // Log to history
        NewsletterHistory::create([
            'subject' => $request->subject,
            'content' => $request->content,
            'recipient_count' => $subscribers->count(),
            'sent_by' => Auth::id(),
        ]);

        return redirect()->route('admin.newsletter.history')->with('success', 'Newsletter envoyée avec succès à ' . $subscribers->count() . ' abonnés.');
    }

    public function destroy(NewsletterSubscription $subscription)
    {
        $subscription->delete();
        return back()->with('success', 'Abonné supprimé avec succès.');
    }

    public function toggleStatus(NewsletterSubscription $subscription)
    {
        $subscription->update([
            'is_active' => !$subscription->is_active
        ]);
        return back()->with('success', 'Statut de l\'abonnement mis à jour.');
    }
}
