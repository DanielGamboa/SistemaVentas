<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\VentaLinea;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NumeroReferencia extends Model
{
    use HasFactory;

    protected $fillable = ['numeroreferencia', 'contacto'];

    protected $cast = [
        
    ];

    // set up pivot table between VentasLineas and NumeroReferencias
    public function ventasLineas(): BelongsToMany

    {
        return $this->belongsToMany(VentaLinea::class);
    }
}
