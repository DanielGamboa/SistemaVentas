<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distrito extends Model
{
    use HasFactory;

    public function getCustomDistritoAttribute()
    {
        return $this->where([
            'provincia_id' => $this->provincia_id,
            'canton_id' => $this->canton_id,
            'distrito_id' => $this->distrito_id,
        ])->value('DistritoName');
    }

    public function provincias(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'provincias_id');
    }

    public function cantones(): BelongsTo
    {
        return $this->belongsTo(Cantone::class, 'cantones_id', 'id');
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class, 'distrito');
    }
}
