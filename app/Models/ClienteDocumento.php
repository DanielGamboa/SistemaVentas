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
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//Change Model to Pivot
class ClienteDocumento extends Pivot implements HasMedia
{
    //Spatie Media Library
    use InteractsWithMedia;

    use HasFactory;
    use Notifiable;



 

    protected $fillable = [
        //Cliente
        // Images Documents Tab
        'tipo_documento_img',
        // 'model_id',
        'documento_img',
        'imagen_doc',
    ];

    protected $casts = [
        'tipo_documento_img' => ImagenesDocumentoEnum::class,
        // 'model_id' => '1',

    ];


    public function cliente(): BelongsTo
    {
    return $this->belongsTo(Cliente::class);
    }
    
    public function addMultipleMediaFromRequest(array $keys): void
    {
        $this->addMultipleMediaFromRequest($keys)
            ->each(function (Media $media) {
                $media->toMediaCollection('clientedocumento');
            });
    }
    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('clientedocumento');
    //     // $this->addMediaCollection(Cliente::class);
    // }
    
    // public function registerMediaConversions(Media $media = null): void
    // {
    //     $this
    //         ->addMediaConversion('preview')
    //         ->fit(Manipulations::FIT_CROP, 300, 300)
    //         ->nonQueued();
    // }

    // public function beforeSave($record, $data)
    // {
    // // Handle file upload for 'imagen_doc'
    // if (isset($data['imagen_doc'])) {
    //     $record
    //         ->addMedia($data['imagen_doc'])
    //         ->toMediaCollection('clientedocumento');
    // }
// }
}
