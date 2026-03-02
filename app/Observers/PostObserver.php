<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\NewsletterSubscription;
use App\Mail\NewsletterMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        if ($post->published_at && $post->published_at <= now()) {
            $this->sendNewsletter($post);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Si l'article vient d'être publié (published_at était null et ne l'est plus)
        if ($post->isDirty('published_at') && $post->published_at && !$post->getOriginal('published_at')) {
            $this->sendNewsletter($post);
        }
    }

    protected function sendNewsletter(Post $post)
    {
        $subscribers = NewsletterSubscription::where('is_active',DB::raw('true'))->get();
        if ($subscribers->isEmpty())
            return;

        $subject = "Nouvelle actualité : " . $post->title;
        $content = "Une nouvelle actualité vient d'être publiée : \n\n" .
            $post->title . "\n\n" .
            strip_tags($post->content) . "\n\n" .
            "Découvrez-en plus sur notre site !";

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterMail($subject, $content));
        }
    }
}
