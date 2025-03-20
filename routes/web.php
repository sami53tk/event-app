<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'role:admin']], function() {
    // Dans un cas de "resource" :
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


// Routes pour admin & organisateur (gestion des événements)
Route::group(['middleware' => ['auth', 'role:admin,organisateur']], function () {
    Route::resource('events', EventController::class);
});

// Route (exemple) pour qu’un client puisse s’inscrire
Route::group(['middleware' => ['auth', 'role:client']], function () {
    // On ajoute une route "register" manuelle
    Route::post('events/{event}/register', [EventController::class, 'register'])
         ->name('events.register');
});

require __DIR__.'/auth.php';
