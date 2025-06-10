<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEventSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:send-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie un récapitulatif aux organisateurs pour les événements terminés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Récupérer les événements qui se sont terminés hier
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $events = Event::whereDate('date', $yesterday)->get();

        $this->info("Traitement de {$events->count()} événements terminés hier.");

        foreach ($events as $event) {
            $organizer = $event->user;

            if (! $organizer) {
                $this->warn("Organisateur non trouvé pour l'événement ID {$event->id}");

                continue;
            }

            // Envoyer l'email de récapitulatif
            Mail::send('emails.event_summary', [
                'user' => $organizer,
                'event' => $event,
            ], function ($message) use ($organizer, $event) {
                $message->to($organizer->email, $organizer->name)
                    ->subject('Récapitulatif de votre événement : '.$event->title);
            });

            $this->info("Récapitulatif envoyé pour l'événement '{$event->title}' à {$organizer->email}");
        }

        $this->info('Envoi des récapitulatifs terminé.');
    }
}
