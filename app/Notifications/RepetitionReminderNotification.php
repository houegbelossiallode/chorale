<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RepetitionReminderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public \App\Models\Repetition $repetition
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): \App\Mail\RepetitionReminderMail
    {
        return (new \App\Mail\RepetitionReminderMail($this->repetition))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'repetition_reminder',
            'title' => 'Rappel : ' . $this->repetition->titre,
            'message' => 'Nous avons une répétition prévue le ' . \Carbon\Carbon::parse($this->repetition->start_time)->translatedFormat('d F Y') . ' à ' . \Carbon\Carbon::parse($this->repetition->start_time)->format('H:i'),
            'repetition_id' => $this->repetition->id,
            'url' => route('choriste.repetitions.repertoire', $this->repetition->id),
        ];
    }
}
