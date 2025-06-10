<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOrganizerReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:send-organizer-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie un rappel aux organisateurs pour les événements qui auront lieu demain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Récupérer les événements qui auront lieu demain
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $events = Event::whereDate('date', $tomorrow)
            ->where('status', 'active')
            ->get();

        $this->info("Traitement de {$events->count()} événements prévus pour demain.");

        foreach ($events as $event) {
            $organizer = $event->user;

            if (! $organizer) {
                $this->warn("Organisateur non trouvé pour l'événement ID {$event->id}");

                continue;
            }

            // Envoyer l'email de rappel à l'organisateur
            Mail::send('emails.organizer_reminder', [
                'organizer' => $organizer,
                'event' => $event,
            ], function ($message) use ($organizer, $event) {
                $message->to($organizer->email, $organizer->name)
                    ->subject('Rappel : votre événement a lieu demain - '.$event->title);
            });

            $this->info("Rappel envoyé à l'organisateur {$organizer->name} pour l'événement '{$event->title}'");
        }

        $this->info('Envoi des rappels aux organisateurs terminé.');
    }
}
