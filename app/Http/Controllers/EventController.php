<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventRegistered;

class EventController extends Controller
{
    /**
     * Affiche la liste des événements
     * (pour admin ou organisateur).
     */
    public function index()
    {
        // Si c'est un admin, on peut tout afficher.
        // Si c'est un organisateur, on affiche seulement ses propres événements.

        $user = Auth::user();

        if ($user->role === 'admin') {
            // Tous les événements
            $events = Event::orderBy('date', 'desc')->get();
        } else {
            // On suppose que $user->role === 'organisateur'
            $events = Event::where('user_id', $user->id)
                           ->orderBy('date', 'desc')
                           ->get();
        }

        // Retourne une vue: resources/views/events/index.blade.php (à créer)
        return view('events.index', compact('events'));
    }

    /**
     * Montre le formulaire de création d'un événement.
     */
    public function create()
    {
        // Retourne la vue resources/views/events/create.blade.php
        return view('events.create');
    }

    /**
     * Enregistre un nouvel événement.
     */
    public function store(Request $request)
    {
        // Validation basique
        $validatedData = $request->validate([
            'title'            => 'required|string|max:255',
            'banner'           => 'nullable|image', // si vous gérez les uploads d’images
            'description'      => 'nullable|string',
            'date'             => 'required|date|after:today',
            'location'         => 'required|string|max:255',
            'status'           => 'required|in:active,annule', // Exemples de statuts
            'max_participants' => 'required|integer|min:1',
            'price'            => 'nullable|numeric|min:0',
            'currency'         => 'nullable|string|size:3',
            // Ajoutez d'autres champs si nécessaire
        ]);

        // Gérer l'upload d'image (optionnel)
        if ($request->hasFile('banner')) {
            // Vous pouvez stocker l'image dans storage/app/public
            // et sauvegarder le chemin dans $validatedData['banner']
            $path = $request->file('banner')->store('banners', 'public');
            $validatedData['banner'] = $path;
        }

        // On définit l'organisateur: si c'est l'admin,
        // soit il crée pour lui-même, soit vous pouvez ajouter un champ "organisateur_id" dans le formulaire, etc.
        // Pour simplifier, on suppose que l'admin crée aussi l'événement "pour lui" ou "pour un organisateur donné".
        // Ici, on lie l'event à l'utilisateur connecté (admin ou organisateur).
        $validatedData['user_id'] = Auth::id();

        // Créer l'événement
        Event::create($validatedData);

        // Redirection
        return redirect()->route('events.index')
                         ->with('success', 'Événement créé avec succès !');
    }

    /**
     * Affiche un événement en particulier.
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        // Retourner la vue resources/views/events/show.blade.php
        return view('events.show', compact('event'));
    }

    /**
     * Formulaire d'édition pour un événement existant.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $user  = Auth::user();

        // Vérifier si user = admin OU l'organisateur propriétaire de l'événement
        if ($user->role !== 'admin' && $event->user_id !== $user->id) {
            abort(403, 'Vous n’êtes pas autorisé à modifier cet événement.');
        }

        return view('events.edit', compact('event'));
    }

    /**
     * Met à jour un événement existant.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user  = Auth::user();

        // Vérifier si user = admin OU l'organisateur propriétaire de l'événement
        if ($user->role !== 'admin' && $event->user_id !== $user->id) {
            abort(403, 'Vous n’êtes pas autorisé à modifier cet événement.');
        }

        // Validation
        $validatedData = $request->validate([
            'title'            => 'required|string|max:255',
            'banner'           => 'nullable|image',
            'description'      => 'nullable|string',
            'date'             => 'required|date',
            'location'         => 'required|string|max:255',
            'status'           => 'required|in:active,annule',
            'max_participants' => 'required|integer|min:1',
            'price'            => 'nullable|numeric|min:0',
            'currency'         => 'nullable|string|size:3',
        ]);

        // Gérer l'upload d'image (optionnel)
        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', 'public');
            $validatedData['banner'] = $path;
        }

        // Mise à jour
        $event->update($validatedData);

        if ($validatedData['status'] === 'annule') {
            foreach ($event->participants as $participant) {
                Mail::to($participant->email)->send(new \App\Mail\EventCancelled($event, $participant));
            }
        }

        return redirect()->route('events.index')
                         ->with('success', 'Événement mis à jour !');
    }

    /**
     * Supprime un événement.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $user  = Auth::user();

        // Vérifier si user = admin OU l'organisateur propriétaire de l'événement
        if ($user->role !== 'admin' && $event->user_id !== $user->id) {
            abort(403, 'Vous n’êtes pas autorisé à supprimer cet événement.');
        }

        $event->delete();
        return redirect()->route('events.index')
                         ->with('success', 'Événement supprimé !');
    }

    /**
     * (Optionnel) Méthode pour que le client s'inscrive à un événement.
     * Vous pouvez la mettre ici ou dans un contrôleur dédié à l'inscription.
     */
    public function register($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        // Vérifier que le user est "client" (ou tout autre logique d'accès)
        if ($user->role !== 'client') {
            abort(403, 'Seuls les clients peuvent s’inscrire aux événements.');
        }

        // Vérifier s'il reste de la place
        if ($event->participants()->count() >= $event->max_participants) {
            return redirect()->back()->with('error', 'Cet événement est complet.');
        }

        // Vérifier que le user n'est pas déjà inscrit
        if ($event->participants->contains($user->id)) {
            return redirect()->back()->with('error', 'Vous êtes déjà inscrit à cet événement.');
        }

        // Si l'événement est payant, rediriger vers la page de paiement
        if ($event->price && $event->price > 0) {
            return redirect()->route('payment.show', $event->id);
        }

        // Inscription (pour événement gratuit)
        $event->participants()->attach($user->id);
        // Envoi d'un email de confirmation (optionnel)
        Mail::to($user->email)->send(new EventRegistered($event));

        // Mail::to($event->user->email)->send(new EventRegistered($event));

        // Redirection
        return redirect()->back()
                         ->with('success', 'Inscription réalisée avec succès !');
    }


    /**
     * (Optionnel) Méthode pour que le client se desinscrive à un événement.
     * Vous pouvez la mettre ici ou dans un contrôleur dédié à l'inscription.
     */

    public function unregister($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        // Vérifier que le user est "client" (ou tout autre logique d'accès)
        if ($user->role !== 'client') {
            abort(403, 'Seuls les clients peuvent se désinscrire aux événements.');
        }

        // Vérifier que le user est inscrit
        if (!$event->participants->contains($user->id)) {
            return redirect()->back()->with('error', 'Vous n’êtes pas inscrit à cet événement.');
        }

        // Désinscription
        $event->participants()->detach($user->id);

        // Redirection
        return redirect()->back()
                         ->with('success', 'Désinscription réalisée avec succès !');
    }

    public function publicIndex(Request $request)
    {
        // On récupère les paramètres du formulaire de recherche
        $search = $request->input('search');
        $status = $request->input('status');
        $location = $request->input('location');

        // On veut seulement les événements encore actifs, par exemple
        // => Sinon, on pourrait afficher tous selon vos règles.
        $query = Event::query()->where('status', 'active');

        // Filtre par titre / description si "search" est renseigné
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
            });
        }

        // Filtre par location
        if ($location) {
            $query->where('location', 'like', "%$location%");
        }

        // Filtre par statut (si vous souhaitez afficher 'annule' aussi ?).
        // Ici, on a déjà forcé where('status', 'active'). Mais si vous souhaitez
        // autoriser un dropdown "active"/"annule", on peut faire :
        if ($status) {
            $query->where('status', $status);
        }

        // Exemple : Vous pourriez aussi filtrer par date >= today
        // $query->where('date', '>=', now());

        // Récupération triée par date (asc), par exemple
        $events = $query->orderBy('date', 'asc')->paginate(10);

        return view('events.index_public', compact('events'));
    }

}
