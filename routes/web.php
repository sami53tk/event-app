<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->role === 'admin') {
        return redirect()->route('dashboard.admin');
    } elseif ($user->role === 'organisateur') {
        return redirect()->route('dashboard.organisateur');
    } else {
        return redirect()->route('dashboard.client');
    }
})->middleware(['auth'])->name('dashboard');



// Exemple de route de profil (fourni par Breeze ou par vous)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Redirection dynamique vers le bon dashboard, selon le rôle
/*
 * Dashboards par rôle
 */

 Route::middleware(['auth'])->group(function () {
    // Pour les dashboards admin et organisateur, tu gardes tes routes actuelles
    Route::get('/dashboard/admin', function () {
        return view('dashboard.admin');
    })->name('dashboard.admin');

    Route::get('/dashboard/organisateur', function () {
        return view('dashboard.organisateur');
    })->name('dashboard.organisateur');

    // Pour le dashboard client, on passe par le contrôleur
    Route::get('/dashboard/client', [ClientDashboardController::class, 'index'])
         ->name('dashboard.client');
});
/*
 * Routes d'admin pour gérer les utilisateurs
 */
Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::resource('admin/users', UserController::class)->names([
        'index'   => 'admin.users.index',
        'create'  => 'admin.users.create',
        'store'   => 'admin.users.store',
        'show'    => 'admin.users.show',
        'edit'    => 'admin.users.edit',
        'update'  => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
});

/*
 * Routes pour admin & organisateur (gestion des événements)
 */
Route::group(['middleware' => ['auth', 'role:admin,organisateur']], function () {
    Route::resource('events', EventController::class)->names([
        'index'   => 'events.index',
        'create'  => 'events.create',
        'store'   => 'events.store',
        'edit'    => 'events.edit',
        'update'  => 'events.update',
        'destroy' => 'events.destroy',
    ]);
});

Route::get('/public-events', [\App\Http\Controllers\EventController::class, 'publicIndex'])
     ->name('public.events.index');

Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])
     ->name('events.show');
/*
 * Route pour qu’un client puisse s’inscrire à un événement
 */
Route::group(['middleware' => ['auth', 'role:client']], function () {
    Route::post('events/{event}/register', [EventController::class, 'register'])->name('events.register');
});

Route::group(['middleware' => ['auth', 'role:client']], function () {
    Route::delete('events/{event}/unregister', [EventController::class, 'unregister'])->name('events.unregister');});


// Routes d'authentification Laravel / Breeze / Fortify (vous en avez déjà)
require __DIR__.'/auth.php';
