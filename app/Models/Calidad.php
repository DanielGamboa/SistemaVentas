<?php

namespace App\Models;

use App\Enums\Calidad\MotivoEvaluacionEnum;
use App\Enums\Calidad\BienvenidaEnum;
use App\Enums\Calidad\DiccionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
// Spatie MediaLibrary
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
//  Spatie MediaLibrary For registerMediaConversions
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\CalidadAuditoria;
// use App\Models\CalidadAuditorium;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\VentaLinea;
use App\Models\User;

class Calidad extends Model implements HasMedia
{
    //Spatie Media Library
    use InteractsWithMedia;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

            /**
             *  Define as fillable field
             * 
             * bienvenida
             * empatia
             * sondeo
             * escucha_activa
             * oferta_comercial
             * numero_alternativo
             * aclara_dudas_cliente
             * manejo_objeciones
             * genera_ventas_irregulares
             * aceptacion_servicio
             * tecnicas_cierre
             * utiliza_tecnicas_cierre
             * validacion_venta
             * diccion
             * empatia_evalucion_agente
             * espera_vacios
             * escucha_activa
             * evita_maltrato
             * abandono_llamada
             * liberty_negativo 
            */
    /**
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'user_id',
        'agente',
        'fecha_llamada',
        'dia_hora_inicio',
        'dia_hora_final',
        'motivo_evaluacion',
        'tlf', // 'tlf' is the phone number to be audited
        'venta_lineas_id',
        'observaciones',
        'evaluacion_completa',
        // Calidad Evaluation
        'bienvenida',
        'empatia',
        'sondeo',
        'escucha_activa',
        'oferta_comercial',
        'numero_alternativo',
        'aclara_dudas_cliente',
        'manejo_objeciones',
        'genera_ventas_irregulares',
        'aceptacion_servicio',
        'tecnicas_cierre',
        'utiliza_tecnicas_cierre',
        'validacion_venta',
        'diccion',
        'empatia_evalucion_agente',
        'espera_vacios',
        'escucha_activa',
        'evita_maltrato',
        'abandono_llamada',
        'liberty_negativo',
        'calificacion',
        
        'created_at',
        'updated_at',
        // Radio
        'ventas_telefono',
        'venta_lineas_id',
        // test
        'grabacion',
        'fecha_llamada',
        

    ];

        /**
             * Cast as array for each field
             * 
             * bienvenida
             * empatia
             * sondeo
             * escucha_activa
             * oferta_comercial
             * numero_alternativo
             * aclara_dudas_cliente
             * manejo_objeciones
             * genera_ventas_irregulares
             * aceptacion_servicio
             * tecnicas_cierre
             * utiliza_tecnicas_cierre
             * validacion_venta
             * diccion
             * empatia_evalucion_agente
             * espera_vacios
             * escucha_activa
             * evita_maltrato
             * abandono_llamada
             * liberty_negativo 
            */
    /**
     * Write code on Method
     *
     * @return response()
     *
     * @var array
     */
    protected $casts = [
        'motivo_evaluacion' => MotivoEvaluacionEnum::class,
        'bienvenida' => 'array',
        'empatia' => 'array',
        'sondeo' => 'array',
        'escucha_activa' => 'array',
        'oferta_comercial' => 'array',
        'numero_alternativo' => 'array',
        'aclara_dudas_cliente' => 'array',
        'manejo_objeciones' => 'array',
        'genera_ventas_irregulares' => 'array',
        'aceptacion_servicio' => 'array',
        'tecnicas_cierre' => 'array',
        'utiliza_tecnicas_cierre' => 'array',
        'validacion_venta' => 'array',
        'diccion' => 'array',
        'empatia_evalucion_agente' => 'array',
        'espera_vacios' => 'array',
        'escucha_activa' => 'array',
        'evita_maltrato' => 'array',
        'abandono_llamada' => 'array',
        'liberty_negativo' => 'array',

    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agente(): BelongsTo
    {
        // Agente is the user_id from the users table
        return $this->belongsTo(User::class);
        // return $this->belongsTo(User::class, 'agente');
        // return $this->belongsTo(User::class);
    }
    

    public function VentaLinea(): BelongsTo
    {
        return $this->belongsTo(VentaLinea::class, 'venta_lineas_id');
    }

    // public function registerMediaConversions(Media $media = null): void
    // {
    //     $this
    //         ->addMediaConversion('preview')
    //         ->fit(Manipulations::FIT_CROP, 300, 300)
    //         ->nonQueued();
    // }

    // Upload Multiple Audio Files
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('Audio')
            ->acceptsMimeTypes(['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3'])
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
    $this
        ->addMediaConversion('preview')
        ->nonQueued();
    }
    public function addMultipleMediaFromRequest(array $keys): void
    {
        $this
            // ->addMultipleMediaFromRequest($keys)
            ->each(function (Media $media) {
                $media->toMediaCollection('ImagenesDocumentos');
            });
    }

    // create a privot table for a one to many relationship between Calidad and GrabacionAuditoria

    public function grabacionauditoria(): HasMany
    {
        return $this->hasMany(CalidadAuditoria::class);
    }

    public function foo(): BelongsTo
    {
        return $this->belongsTo(CalidadAuditoria::class);
    }
}
