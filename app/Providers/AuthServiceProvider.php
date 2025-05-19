<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Définir les autorisations pour les différents rôles
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('organisateur', function ($user) {
            return $user->role === 'organisateur' || $user->role === 'admin';
        });

        Gate::define('client', function ($user) {
            return $user->role === 'client' || $user->role === 'admin';
        });
    }
}
