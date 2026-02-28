<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Event;
use App\Models\User;
use App\Models\Pupitre;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function index()
    {
        $priest_word = Post::where('type', 'priest_word')->latest()->first();
        $latest_news = Post::where('type', 'news')
            ->whereNotNull('published_at')
            ->latest()
            ->take(3)
            ->get();
        $upcoming_events = Event::where('is_public', DB::raw('true'))
            ->where('start_at', '>=', now())
            ->orderBy('start_at', 'asc')
            ->take(3)
            ->get();

        $testimonies = Post::where('type', 'testimony')->latest()->take(3)->get();
        $members_preview = User::latest()->take(6)->get();

        return view('welcome', compact('priest_word', 'latest_news', 'upcoming_events', 'testimonies', 'members_preview'));
    }

    public function about()
    {
        return view('about');
    }

    public function members()
    {
        $pupitres = Pupitre::with([
            'users' => function ($query) {
                $query->withCount('likesReceived');
            }
        ])->get();
        return view('members', compact('pupitres'));
    }

    public function events()
    {
        $events = Event::where('is_public', DB::raw('true'))
            ->orderBy('start_at', 'desc')
            ->get();
        return view('events', compact('events'));
    }

    public function memberProfile($slug)
    {
        $user = User::withCount('likesReceived')->where('slug', $slug)->firstOrFail();
        $pupitre = Pupitre::find($user->pupitre_id);

        // Vérifier si l'utilisateur actuel (ou IP) a déjà liké
        $hasLiked = \App\Models\Like::where('choriste_id', $user->id)
            ->where(function ($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('ip_address', request()->ip());
                }
            })->exists();

        return view('profile', compact('user', 'pupitre', 'hasLiked'));
    }

    public function toggleLike($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $ip = request()->ip();
        $userId = auth()->id();

        $existingLike = \App\Models\Like::where('choriste_id', $user->id)
            ->where(function ($query) use ($userId, $ip) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('ip_address', $ip);
                }
            })->first();

        if ($existingLike) {
            $existingLike->delete();
            $status = 'unliked';
        } else {
            \App\Models\Like::create([
                'choriste_id' => $user->id,
                'user_id' => $userId,
                'ip_address' => $ip,
            ]);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'likes_count' => $user->likesReceived()->count()
        ]);
    }

    public function eventShow($id)
    {
        $event = Event::with('images')->where('id', $id)->first();
        return view('events.show', compact('event'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
    }

    public function newsletterSubscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
        ], [
            'email.unique' => 'Cet email est déjà inscrit à notre newsletter.',
        ]);

        NewsletterSubscription::create($validated);

        return back()->with('success', 'Merci ! Votre inscription à notre newsletter a été confirmée.');
    }
}
