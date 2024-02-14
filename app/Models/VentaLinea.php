<?php

namespace App\Models;

use App\Enums\EstatusVentaLineaEnum;
use App\Enums\PlanesLibertyLineasEnum;
use App\Enums\VentaLineasEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Provincia;
use App\Models\Cantone;
use App\Models\Distrito;
use App\Models\NumeroReferencia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class VentaLinea extends Model
{
    use HasFactory;

    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'VentaLinea',
        'plan',
        'precio',
        'Estatus',
        'tlf',
        'tlf_venta_distinto',
        'user_id',
        // 'venta_linea',
        'tlf_marcado',
        'clientes_id',
        'entrega_distinta',
        'direccion_entrega',
        'provincias_id',
        'cantones_id',
        'distritos_id',
    ];

    /**
     * Write code on Method
     *
     * @return response()
     *
     * @var array
     */
    protected $casts = [
        'Estatus' => EstatusVentaLineaEnum::class,
        'plan' => PlanesLibertyLineasEnum::class,
        'VentaLinea' => VentaLineasEnum::class,
        'tlf_venta_distinto' => 'boolean',
        'entrega_distinta' => 'boolean',

    ];

    // Relationships to other tables, remember to import the model in order to use the class

    // Relationship Between Users and Ventas Lineas
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'clientes_id');
    }

    public function provincias(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function cantona(): BelongsTo
    {
        // return $this->belongsTo(Cantone::class);
        return $this->belongsTo(Cantone::class, 'cantones_id');
        // return $this->belongsTo(Cantone::class, 'CantonNumber');
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class, 'distritos_id');
    }

    // Set up relationship to NumeroReferencia
    public function numeroReferencias(): BelongsToMany
    {
        return Cache::remember('numeroReferencias', 60*60*24*30*6, function() {
            return $this->belongsToMany(NumeroReferencia::class)->get();
        });

        
        // return $this->belongsToMany(NumeroReferencia::class);
    }

}
