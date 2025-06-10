<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Affiche la liste de tous les utilisateurs avec fonctionnalité de recherche.
     */
    public function index(Request $request)
    {
        // Récupérer les paramètres de recherche
        $search = $request->input('search');
        $role = $request->input('role');

        // Construire la requête
        $query = User::query();

        // Appliquer le filtre de recherche si présent
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtrer par rôle si spécifié
        if ($role) {
            $query->where('role', $role);
        }

        // Récupérer les utilisateurs avec pagination
        $users = $query->orderBy('id', 'asc')->paginate(10);

        // Retourne une vue avec les utilisateurs filtrés
        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur.
     */
    public function create()
    {
        // Retourne une vue, par exemple: resources/views/admin/users/create.blade.php
        return view('admin.users.create');
    }

    /**
     * Traite les données du formulaire pour créer un utilisateur.
     */
    public function store(Request $request)
    {
        // Validation basique
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,organisateur,client',
        ]);

        // Création de l'utilisateur
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],  // par défaut 'client', ou selon le formulaire
        ]);

        // Redirection
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès !');
    }

    /**
     * Affiche les détails d'un utilisateur (optionnel si besoin).
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition pour un utilisateur existant.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Retourne une vue, ex: resources/views/admin/users/edit.blade.php
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Met à jour les informations d'un utilisateur existant.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validation
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,organisateur,client',
            'password' => 'nullable|string|min:8',
        ]);

        // Mise à jour
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];

        // Si l'admin renseigne un nouveau mot de passe
        if (! empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès !');
    }

    /**
     * Supprime un utilisateur.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès !');
    }
}
