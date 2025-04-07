<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function showCheckoutPage($eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        if (!$event->price || $event->price <= 0) {
            return redirect()->route('events.show', $event->id)
                             ->with('error', 'Cet événement est gratuit, pas besoin de paiement.');
        }

        if ($user->role !== 'client') {
            abort(403, 'Seuls les clients peuvent s\'inscrire aux événements.');
        }

        if ($event->participants()->count() >= $event->max_participants) {
            return redirect()->back()->with('error', 'Cet événement est complet.');
        }

        if ($event->participants->contains($user->id)) {
            return redirect()->back()->with('error', 'Vous êtes déjà inscrit à cet événement.');
        }

        return view('payments.checkout', compact('event'));
    }

    public function createCheckoutSession(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        if (!$event->price || $event->price <= 0) {
            return redirect()->route('events.show', $event->id)
                             ->with('error', 'Cet événement est gratuit, pas besoin de paiement.');
        }

        if ($user->role !== 'client') {
            abort(403, 'Seuls les clients peuvent s\'inscrire aux événements.');
        }

        if ($event->participants()->count() >= $event->max_participants) {
            return redirect()->back()->with('error', 'Cet événement est complet.');
        }

        if ($event->participants->contains($user->id)) {
            return redirect()->back()->with('error', 'Vous êtes déjà inscrit à cet événement.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($event->currency),
                    'product_data' => [
                        'name' => $event->title,
                        'description' => $event->description ?? 'Inscription à l\'événement',
                    ],
                    'unit_amount' => (int)($event->price * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['event' => $event->id]),
            'cancel_url' => route('payment.cancel', ['event' => $event->id]),
            'client_reference_id' => $user->id . '_' . $event->id,
        ]);

        return redirect($session->url);
    }

    public function success(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        if (!$event->participants->contains($user->id)) {
            $event->participants()->attach($user->id);
        }

        return redirect()->route('events.show', $event->id)
                         ->with('success', 'Paiement réussi et inscription confirmée !');
    }

    public function cancel(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        return redirect()->route('events.show', $event->id)
                         ->with('error', 'Le paiement a été annulé. Vous n\'êtes pas inscrit à l\'événement.');
    }
}
