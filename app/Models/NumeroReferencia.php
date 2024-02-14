<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\VentaLinea;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class NumeroReferencia extends Model
{
    use HasFactory;

    protected $fillable = ['numeroreferencia', 'contacto'];

    protected $cast = [
        
    ];

    // set up pivot table between VentasLineas and NumeroReferencias
    // Cache the query for 6 months
    public function ventasLineas(): BelongsToMany

    {
        // $ventasLineas = Cache::remember('ventasLineas', 60*60*24*30*6, function() {
        //     return $this->belongsToMany(VentaLinea::class);
        // });

        // return $ventasLineas;
        return $this->belongsToMany(VentaLinea::class);
    }
}
