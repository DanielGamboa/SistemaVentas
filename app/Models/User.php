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

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
}
