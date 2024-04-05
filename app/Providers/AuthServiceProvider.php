<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Add User model to UserPolicy
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Cliente::class => \App\Policies\ClientePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();
        // Defines the gate directly in the Auth service provider rather than in the UserPolicy
        // Gate::define('exportUsers', function ($user) {
        //     return $user->hasPermissionTo('Exportar usuarios') || $user->email === 'dgamboa@test.com';
        // });
        // This will work and referance the UserPolicy and check if the user has the permission to exportUsers
        // Defined the gates for the policies directly in the Resource for visibility.
        // Gate::define('exportUsers', [\App\Policies\UserPolicy::class, 'exportUsers']);
    }
}
