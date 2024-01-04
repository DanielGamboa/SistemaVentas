<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\VentaLinea;
use App\Enums\Calidad\BienvenidaEnum;
use App\Enums\Calidad\EmpatiaEnum;
use App\Enums\Calidad\DiccionEnum;
use App\Enums\Calidad\MotivoEvaluacionEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calidad extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;
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

    ];

        /**
     * Write code on Method
     *
     * @return response()
     * @var array
     */
    protected $casts = [
        'motivo_evaluacion' => MotivoEvaluacionEnum::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function VentaLinea(): BelongsTo
    {
        return $this->belongsTo(VentaLinea::class, 'venta_lineas_id');
    }
}