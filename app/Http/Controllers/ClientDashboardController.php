<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        // Récupère l'utilisateur connecté
        $user = Auth::user();

        // Récupère les événements auxquels le client est inscrit,
        // triés par date ascendante par exemple
        $events = $user->participatedEvents()->orderBy('date', 'asc')->get();

        // Retourne la vue en passant les événements
        return view('dashboard.client', compact('events'));
    }
}
