<?php

namespace App\Models;

use App\Enums\ImagenesDocumentoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Notifications\Notifiable;

// Prepare for use with Spatie Media Library
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;

//Change Model to Pivot
class ClienteDocumento extends Pivot implements HasMedia
{
    //Spatie Media Library
    use InteractsWithMedia;

    use HasFactory;
    use Notifiable;

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('clientedocumento');
    }

    protected $fillable = [
        //Cliente
        // Images Documents Tab
        'tipo_documento_img',
        'model_id',
        'documento_img',
        'imagen_doc',
    ];

    protected $casts = [
        'tipo_documento_img' => ImagenesDocumentoEnum::class,
        'model_id' => '1',

    ];
}
