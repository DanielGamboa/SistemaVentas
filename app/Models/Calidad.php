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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

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
             * escucha_activa_evaluacion_agente
             * evita_maltrato
             * abandono_llamada
             * liberty_negativo 
            */
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'user_id', // 'user_id' is the auditor
        'agente', // 'agente' is the user_id from the users table that is being audited
        'fecha_llamada', // 'fecha_llamada' is the date of the call
        'dia_hora_inicio', // 'dia_hora_inicio' is the start date and time of the call
        'dia_hora_final', // 'dia_hora_final' is the end date and time of the call
        'motivo_evaluacion', // MotivoEvaluacionEnum reason for audit
        'tlf', // 'tlf' is the phone number to be audited
        'venta_lineas_id', // 'venta_lineas_id' is the id of the sale line
        'observaciones', // 'observaciones' is the observations made by the auditor
        'evaluacion_completa', // 'evaluacion_completa' is a bool the complete evaluation
        // Calidad Evaluation enums
        'bienvenida', // BienvenidaEnum is the start of the conversation
        'empatia', // EmpatiaEnum is the empathy of the agent
        'sondeo', // SondeoEnum is the survey of the agent
        'escucha_activa', // EscuchaActivaEnum is the active listening of the agent
        'oferta_comercial', // OfertaComercialEnum is the commercial offer of the agent
        'numero_alternativo', // NumeroAlternativoEnum is the alternative number of the agent
        'aclara_dudas_cliente', // AclaraDudasClienteEnum is the clarification of doubts of the agent
        'manejo_objeciones', // ManejoObjecionesEnum is the handling of objections of the agent 
        'genera_ventas_irregulares', // GeneraVentasIrregularesEnum is the generation of irregular sales of the agent
        'aceptacion_servicio', // AceptacionServicioEnum is the acceptance of service of the agent
        'tecnicas_cierre',  // TecnicasCierreEnum is the closing techniques of the agent
        'utiliza_tecnicas_cierre', // UtilizaTecnicasCierreEnum is the use of closing techniques of the agent
        'validacion_venta', // ValidacionVentaEnum is the validation of the sale of the agent
        'diccion', // DiccionEnum is the diction of the agent
        'empatia_evalucion_agente', // EmpatiaEnum is the empathy of the agent
        'espera_vacios', // EsperaVaciosEnum is the waiting for empty spaces of the agent
        'escucha_activa_evaluacion_agente', // EscuchaActivaEnum is the active listening of the agent
        'evita_maltrato', // EvitaMaltratoEnum is the avoidance of mistreatment of the agent
        'abandono_llamada', // AbandonoLlamadaEnum is the abandonment of the call of the agent
        'liberty_negativo', // LibertyNegativoEnum is the negative liberty of the agent
        'calificacion', // 'calificacion' is the score of the agent
        
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
        'escucha_activa_evaluacion_agente' => 'array',
        'evita_maltrato' => 'array',
        'abandono_llamada' => 'array',
        'liberty_negativo' => 'array',

    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agente(): BelongsTo
    {
        // Agente is the user_id from the users table
        return $this->belongsTo(User::class, 'agente');
        // return $this->belongsTo(User::class, 'agente');
        // return $this->belongsTo(User::class);
    }
    

    public function VentaLinea(): BelongsTo
    {
        return $this->belongsTo(VentaLinea::class, 'venta_lineas_id');
        // This belongs to VentaLinea, where im getting the id from the VentaLinea table from the column ventas_telefono on Calidad

        // return $this->belongsTo(VentaLinea::class, 'id','ventas_telefono');
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

    public function scopeFilterByUserPermission(Builder $query): Builder
    {
        // Get the current user
        $user = auth()->user();

        // If the user has the "Calidad ver todos" permission, return the base query
        if (Gate::allows('viewAllCalidad', $user)) {
            return $query;
        }

        // Otherwise, only include records created by the user or where the user is the "agente"
        return $query->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('agente', $user->id);
        });
    }

}
