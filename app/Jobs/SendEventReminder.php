<?php

namespace App\Jobs;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEventReminder implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Exécute le job.
     */
    public function handle()
    {
        // Récupère les événements dont la date est dans moins de 24 heures
        $events = Event::where('date', '>=', Carbon::now())
                       ->where('date', '<=', Carbon::now()->addDay())
                       ->get();

        foreach ($events as $event) {
            // Envoie un email de rappel à chaque participant
            foreach ($event->participants as $user) {
                Mail::to($user->email)->send(new \App\Mail\EventReminder($event, $user));
            }
        }
    }
}
