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

class Calidad extends Model implements HasMedia
{
    //Spatie Media Library
    use InteractsWithMedia;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'user_id',
        'fecha_llamada',
        'dia_hora_inicio',
        'dia_hora_final',
        'motivo_evaluacion',
        'venta_lineas_id',
        'observaciones',
        'evaluacion_completa',
        'bienvenida',
        'empatia',
        'diccion',
        'created_at',
        'updated_at',
        // Radio
        'ventas_telefono',
        // test
        'grabacion',
        'fecha_llamada',
        

    ];

    /**
     * Write code on Method
     *
     * @return response()
     *
     * @var array
     */
    protected $casts = [
        'motivo_evaluacion' => MotivoEvaluacionEnum::class,
        'bienvenida' => BienvenidaEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function VentaLinea(): BelongsTo
    {
        return $this->belongsTo(VentaLinea::class, 'venta_lineas_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
