<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\NewsletterSubscription;
use App\Mail\NewsletterMail;
use Illuminate\Support\Facades\Mail;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        if ($event->is_public) {
            $this->sendNewsletter($event);
        }
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // Si l'événement vient de passer en public
        if ($event->isDirty('is_public') && $event->is_public) {
            $this->sendNewsletter($event);
        }
    }

    protected function sendNewsletter(Event $event)
    {
        $subscribers = NewsletterSubscription::where('is_active', true)->get();
        if ($subscribers->isEmpty())
            return;

        $subject = "Nouvel événement : " . $event->title;
        $content = "Un nouvel événement est disponible : \n\n" .
            $event->title . "\n\n" .
            "Date : " . $event->start_at->translatedFormat('l d F Y') . " à " . $event->start_at->format('H:i') . "\n" .
            "Lieu : " . ($event->location ?? 'Non précisé') . "\n\n" .
            $event->description . "\n\n" .
            "Retrouvez tous les détails sur notre site !";

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterMail($subject, $content));
        }
    }
}
