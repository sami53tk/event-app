<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    /**
     * Crée une nouvelle instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Définir le contenu de l’email.
     */
    public function build()
    {
        return $this->subject('Confirmation d\'inscription à l\'événement')
                    ->view('emails.event_registered');
    }
}
