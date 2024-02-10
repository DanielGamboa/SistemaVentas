<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            // Created a listener for the User model
            'eloquent.created: App\User' => [
                'App\Listeners\ClearUserCache',
            ],
            // Updated a listener for the User model
            'eloquent.updated: App\User' => [
                'App\Listeners\ClearUserCache',
            ],
            // Created a listener for the Cliente model
            'eloquent.created: App\Models\Cliente' => [
                'App\Listeners\ClearClienteCache',
            ],
            // Updated a listener for the Cliente model
            'eloquent.updated: App\Models\Cliente' => [
                'App\Listeners\ClearClienteCache',
            ],
            // Created a listener for the Calidad model
            'eloquent.created: App\Models\Calidad' => [
                'App\Listeners\ClearCalidadCache',
            ],
            // Updated a listener for the Calidad model
            'eloquent.updated: App\Models\Calidad' => [
                'App\Listeners\ClearCalidadCache',
            ],
            // Created a listener for the Provincia model
            'eloquent.created: App\Models\Provincia' => [
                'App\Listeners\ClearProvinciaCache',
            ],
            // Updated a listener for the Provincia model
            'eloquent.updated: App\Models\Provincia' => [
                'App\Listeners\ClearProvinciaCache',
            ],
            // Created a listener for the Canton model
            'eloquent.created: App\Models\Canton' => [
                'App\Listeners\ClearCantonCache',
            ],
            // Updated a listener for the Canton model
            'eloquent.updated: App\Models\Canton' => [
                'App\Listeners\ClearCantonCache',
            ],
            // Created a listener for the Distrito model
            'eloquent.created: App\Models\Distrito' => [
                'App\Listeners\ClearDistritoCache',
            ],
            // Updated a listener for the Distrito model
            'eloquent.updated: App\Models\Distrito' => [
                'App\Listeners\ClearDistritoCache',
            ],
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
