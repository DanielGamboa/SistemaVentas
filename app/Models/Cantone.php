<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Provincia;
use App\Models\Cliente;
use Apo\Models\Distrito;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Cantone extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'CantonNumber', 'canton', 'id_provincias'
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
