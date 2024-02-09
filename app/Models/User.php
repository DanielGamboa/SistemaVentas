<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\VentaLinea;
use App\Models\GrabacionAuditoria;

// Add for production
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;


class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        // return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'cedula',
        'usuario',
        'fecha_ingreso',
        'estado',
        'role',
        'tlf',
        'password',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships to other Models

    // VentasLineas

    public function ventaslineas(): HasMany
    {
        return $this->hasMany(VentaLinea::class);
    }

    // GrabacionAuditoria relationship
    public function grabacionauditoria(): HasMany
    {
        return $this->hasMany(GrabacionAuditoria::class);
    }

    // Calidad relationship
    public function calidad(): HasMany
    {
        return $this->hasMany(Calidad::class, 'agente', 'id');
    }

    //Calidad user relation
    public function calidadUser(): HasMany
    {
        return $this->hasMany(Calidad::class, 'user_id', 'id');
    }

    // Live wire component test --> app/Livewire/DatabaseNotifications.php and resources/views/notifications/database_notifications-trigger.blade.php
    public function myfoo(): void
{
        User::create($this->form->getState());
}
}
