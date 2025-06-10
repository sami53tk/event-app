<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Jenssegers\Agent\Agent;

class SendLoginNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Obtenir l'adresse IP
        $ipAddress = request()->ip() ?? 'Inconnue';

        // Obtenir les informations sur le navigateur
        $agent = new Agent();
        $browser = $agent->browser().' '.$agent->version($agent->browser());

        // Envoyer l'email de notification de connexion
        Mail::send('emails.login_notification', [
            'user' => $user,
            'ipAddress' => $ipAddress,
            'browser' => $browser,
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Nouvelle connexion à votre compte Event App');
        });
    }
}
