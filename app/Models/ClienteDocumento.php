<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Notifications\Notifiable;
use App\Enums\ImagenesDocumentoEnum;


//Change Model to Pivot
class ClienteDocumento extends Pivot
{
    use HasFactory;
    use Notifiable;


    protected $fillable = [
        //Cliente
        // Images Documents Tab
        'tipo_documento_img'
    ];

    protected $casts = [
        'tipo_documento_img' => ImagenesDocumentoEnum::class,

    ];
}
