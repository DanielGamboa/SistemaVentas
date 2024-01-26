<?php

// we are going to create a privot table for a one to many relationship between Calidad and GrabacionAuditoria


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Calidad;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
// class CalidadAuditoria extends Pivot implements HasMedia
class CalidadAuditoria extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    use Notifiable;

    use SoftDeletes;


    protected $fillable = [
        // 'calidad_id', // 'calidad_id' is the foreign key in the pivot table
        // 'user_id', // 'user_id' is the foreign key in the pivot table
        'grabacion', // Recording file name
        'original_filename', // Original file name
        'fecha_grabacion', // Recording date
        'fecha_llamada', // Call date fecha_llamada
        'dia_hora_inicio', // Call start time
        'dia_hora_final', // Call end time
        'hours', // Call duration hours
        'minutes', // Call duration minutes
        'seconds', // Call duration seconds
        'duracion', // Call duration
        'created_at',
        'updated_at',
    ];

    protected $casts = [

    ];

    public function calidad(): BelongsTo
    {
        return $this->belongsTo(Calidad::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
