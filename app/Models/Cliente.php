<?php

namespace App\Models;

use App\Enums\ImagenesDocumentoEnum;
use App\Enums\TipoDocumentoEnum;
use Database\Factories\ClienteFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
//  For registerMediaConversions for Spatie MediaLibrary
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


// class Cliente extends Model <-- previus
// class Cliente extends Model implements HasMedia
class Cliente extends Model
{
    //Spatie Media Library
    // use InteractsWithMedia;
    // Factory
    use HasFactory;
    //
    use Notifiable;
    use SoftDeletes;


//     public function registerMediaConversions(Media $media = null): void
// {
//     $this
//         ->addMediaConversion('preview')
//         ->fit(Manipulations::FIT_CROP, 300, 300)
//         ->nonQueued();
// }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     * @return response()
     */
    protected $fillable = [
        //Cliente
        'documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'direccion',
        // 'direccion_entrega',
        'tipo_documento',
        'provincias_id',
        'cantones_id',
        'distritos_id',
        // 'entrega_distinta',
        // 'direccion_entrega',
        'documento_completo',
        'documento_img',
        'imagen_doc',
        // On create Logged in user is added
        'user_id',
        // Images Documents Tab
        // 'tipo_documento_img',
    ];

    /**
     * Write code on Method
     *
     * @return response()
     *
     * @var array
     */
    protected $casts = [
        'tipo_documento' => TipoDocumentoEnum::class,
        'documento_img' => ImagenesDocumentoEnum::class,
        'documento_completo' => 'boolean',

    ];

    // public function getCustomDistritoAttribute($provinciaId, $cantonId, $distritoId)
    // {
    //     return Distrito::where([
    //         'provincia_id' => $this->provincia_id,
    //         'canton_id' => $this->canton_id,
    //         'distrito_id' => $this->distrito_id,
    //     ])->value('DistritoName');
    // }

    public function provincias(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function cantona(): BelongsTo
    {
        // $provincia = $this->provincias_id;
        // dd($provincia);
        // return $this->belongsTo(Cantone::class, 'cantones_id', 'CantonNumber',);
        return $this->belongsTo(Cantone::class, 'cantones_id');

        // ->where('id_provincias', $provincia)->value('canton');

        // ->where('provincia_id', 'id_provincias');
        // return $this->belongsTo(Cantone::class, 'CantonNumber');
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class, 'distritos_id');
    }

    // Clientes belong to user -- Vendedor
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(VentaLinea::class);
    }

    public function clientedocumento(): HasMany
    {
        return $this->hasMany(ClienteDocumento::class);
    }

    public function foo(): BelongsTo
    {
        return $this->belongsTo(Cantone::class, function ($query) {
            $query->where('id_provincias', $this->provincia_id)
                ->where('CantonNumber', $this->canton_id);
        });
        // return $cantones;
    }

    public function fuu(): BelongsTo
    {
        return $this->belongsTo(Cantone::class, 'CantonNumber', 'cantones_id')
            // ->where('provincia_id', 'provincia_id')
            ->where('CantonNumber', 'canton_id');
        // ->value('canton');
        // $cantones = DB::table('cantones')
        //     ->where('id_provincia', 'provincia_id')
        //     ->where('CantonNumber', 'canton_id')
        //     ->value('canton');
        // return $this->$cantones;
    }

    // Faker factory
    /**
     * Create a new factory instance for the model.
     */
    protected static function ClienteFactory(): Factory
    {
        return ClienteFactory::new();
    }

    // Spatie media library

}
