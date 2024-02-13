<?php

namespace App\Models;

use App\Models\Distrito;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use App\Models\Provincia;
use App\Models\Cliente;


class Cantone extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'CantonNumber', 'canton', 'id_provincias',
    ];

    public function provincias(): BelongsTo
    {
        //id_provincias
        return $this->belongsTo(Provincia::class, 'id_provincias');
    }

    public function distrito(): HasMany
    {
        //id_provincias
        return $this->hasMany(Distrito::class, 'cantones_id', 'id');
    }

    public function cliente(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }
}
