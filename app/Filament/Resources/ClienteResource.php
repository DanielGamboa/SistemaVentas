<?php

namespace App\Filament\Resources;

use App\Actions\Filament\CreateAction;
use Filament\Forms\Actions\Actions;
use Filament\Forms\Components\Actions\Action;
use App\Enums\ImagenesDocumentoEnum;
use App\Enums\TipoDocumentoEnum;
use App\Filament\Resources\ClienteResource\Pages;
use App\Models\Cantone;
use App\Models\Cliente;
use App\Models\Distrito;
use App\Models\Provincia;
use App\Models\ClienteDocumento;
use Filament\Forms;
// Enums
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
// Forms
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
// Tables
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
// Models
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
// Filter methods
use Livewire\Component as Livewire;
// Spatie Media Library
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
// Import CSV to client table
use App\Filament\Imports\ClienteImporter;
use Filament\Tables\Actions\ImportAction;
// Cache 
use Illuminate\Support\Facades\Cache;


class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    // Cache Cliente
    public static function retrieveRecords($request, $model)
    {
        return Cache::remember('clientes', 60 * 24 * 3, function () use ($model) {
            return $model::query()->get();
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Datos')
                            ->schema([
                                // Datos
                                // Datos personales del cliente

                                Section::make()
                                    ->id('datos_cliente')
                                    ->schema([
                                        Select::make('tipo_documento')
                                        ->options(TipoDocumentoEnum::class)
                                        ->enum(TipoDocumentoEnum::class)
                                        ->columnSpan(2)
                                        ->required(),
                                        TextInput::make('documento')
                                            ->columnSpan(2)
                                            ->required()
                                            ->maxLength(30)
                                            ->unique(ignoreRecord: true),
                                        TextInput::make('primer_nombre')
                                            ->autocapitalize()
                                            ->live(onBlur: true) // Will re-render and execute LiveWire componant after the user leaves the field
                                            // ->dehydrateStateUsing(fn (string $state): string => ucwords($state)) // Will capitalize the first letter of every word
                                            // ->dehydrateStateUsing(fn (string $state): string => ucwords($state))
                                            // ->afterStateUpdated(function (Set $set, $state) {
                                                // Set: is a built in function that takes two parameters
                                                // $PrimerNombre is the retrived value in this case the 'primer_nombre' value
                                                // Str::title() will make the first letter of every word capitalized and the rest lower case
                                                // $PrimerNombre = Str::title($state);
                                                // $set('primer_nombre', $PrimerNombre);
                                            // })
                                            ->afterStateUpdated((fn (string $state): string => ucwords($state)))
                                            ->columnSpan(2)
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('segundo_nombre')
                                            ->autocapitalize('words')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                // Set: is a built in function that takes two parameters
                                                // $SegundoNombre is the retrived value in this case the 'segundo_nombre' value
                                                // Str::title() will make the first letter of every word capitalized and the rest lower case
                                                // $SegundoNombre = Str::title($state);
                                                // $set('segundo_nombre', $SegundoNombre);
                                            })
                                            ->columnSpan(2)
                                            ->columnSpan(2)
                                            ->maxLength(255),
                                        TextInput::make('primer_apellido')
                                            ->autocapitalize('words')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                // Set: is a built in function that takes two parameters
                                                // $PrimerApellido is the retrived value in this case the 'primer_apellido' value
                                                // Str::title() will make the first letter of every word capitalized and the rest lower case
                                                // $PrimerApellido = Str::title($state);
                                                // $set('primer_apellido', $PrimerApellido);
                                            })
                                            ->columnSpan(2)
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('segundo_apellido')
                                            ->autocapitalize('words')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                //Primer nombre
                                                $primer_nombre = $get('primer_nombre');
                                                $PrimerNombre = Str::title($primer_nombre);
                                                $set('primer_nombre', $PrimerNombre);
                                                // Segundo Nombre
                                                $segundo_nombre = $get('segundo_nombre');
                                                $SegundoNombre = Str::title($segundo_nombre);
                                                $set('segundo_nombre', $SegundoNombre);
                                                //Primer Apellido
                                                $primer_apellido = $get('primer_apellido');
                                                $PrimerApellido = Str::title($primer_apellido);
                                                $set('primer_apellido', $PrimerApellido);
                                                // Set: is a built in function that takes two parameters
                                                // $SegundoApellido is the retrived value in this case the 'segundo_apellido' value
                                                // Str::title() will make the first letter of every word capitalized and the rest lower case
                                                $SegundoApellido = Str::title($state);
                                                $set('segundo_apellido', $SegundoApellido);
        
                                            })
                                            ->columnSpan(2)
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label('Correo Electrónico')
                                            ->type('email')
                                            ->prefixIcon('heroicon-o-envelope')
                                            ->columnSpan(4)
                                            ->required()
                                            ->unique(ignoreRecord: true),
                                    ])->columns(12)->columnSpan(12),

                                Section::make()->schema([
                                    // Direccion de facturación
                                    RichEditor::make('direccion')
                                    ->label('Dirección')
                                    ->columnSpan(6)
                                    ->disableToolbarButtons([
                                        'blockquote',
                                        'strike',
                                        'link',
                                        'codeBlock',
                                        'attachFiles',
                                    ])
                                    ->required(),
                                ])->columnSpan(6),
                                // Provincia, Canton, distrito de facturacion
                                Section::make()->schema([
                                    // Provincia, Distrito, Canton de facturación
                                    Select::make('provincias_id')
                                    ->label('Provincia')
                                    // ->options(Provincia::all()->pluck('provincia', 'id'))
                                    ->options(Cache::remember('provincias', 525600, function () {
                                        return Provincia::all()->pluck('provincia', 'id');
                                    }))
                                    ->searchable()
                                    ->columnSpan(2)
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('cantones_id', null); //clears Cantones field on change
                                        $set('distritos_id', null); //clears Distritos field on change
                                    })
                                    ->required(),
                                    // Donde dice CantonNumber tenia "id" anteriormente
                                    Select::make('cantones_id')
                                    ->label('Canton')
                                    // ->options(fn (Get $get): Collection => Cantone::query()
                                    // ->where('id_provincias', $get('provincias_id'))
                                    // // This was already commented out ->pluck('canton', 'id'))
                                    // ->pluck('canton', 'CantonNumber'))
                                    ->options(fn (Get $get): Collection => Cache::remember("cantones-{$get('provincias_id')}", 525600, function () use ($get) {
                                        return Cantone::query()
                                            ->where('id_provincias', $get('provincias_id'))
                                            ->pluck('canton', 'CantonNumber');
                                        }))
                                    ->searchable()
                                    // ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('distritos_id', null);
                                    })
                                    ->columnSpan(2)
                                    ->required(),
                                    Select::make('distritos_id')
                                    ->label('Distrito')
                                    // ->options(fn (Get $get): Collection => Distrito::query()
                                    //     ->where('provincias_id', '=', $get('provincias_id'))
                                    //     ->where('cantones_id', $get('cantones_id').'CantonNumber')
                                    //     ->pluck('distrito', 'id')
                                    ->options(fn (Get $get): Collection => Cache::remember("distritos-{$get('provincias_id')}-{$get('cantones_id')}", 525600, function () use ($get) {
                                        return Distrito::query()
                                            ->where('provincias_id', '=', $get('provincias_id'))
                                            ->where('cantones_id', $get('cantones_id').'CantonNumber')
                                            ->pluck('distrito', 'id');
                                    }))
                                    ->searchable()
                                    ->columnSpan(2)
                                    // ->preload()
                                    ->live()
                                    ->required(),
                                ])->columnSpan(6),

                            ])
                            ->icon('heroicon-o-identification'),
                        // Pestaña para cargar las fotos de los documentos
                        Tabs\Tab::make('Imagenes')
                            ->schema([
                                // Forms\Components\Builder::make('content')
                                // ->blocks([
                                //     Forms\Components\Builder\Block::make('clientedocumento')
                                //         ->live()
                                //         ->relationship()
                                //         ->schema([
                                //             Forms\Components\Hidden::make('clientedocumento_id')
                                //                 ->default(fn() => Str::random(12)),
                                //                 Select::make('documento_img')
                                //                 ->live()
                                //                 ->options(ImagenesDocumentoEnum::class),
                                //             Forms\Components\SpatieMediaLibraryFileUpload::make('imagen_doc')
                                //                 // ->whereCustomProperties(fn(Forms\Get $get) => ['clientedocumento_id' => $get('clientedocumento_id')])
                                //                 ->customProperties(fn(Forms\Get $get) => ['clientedocumento_id' => $get('clientedocumento_id')])
                                //                 ->multiple()
                                //                 ->collection('cliente_documentos')
                                //         ]),
                                // ])->columnSpan(12),

                                // SpatieMediaLibraryFileUpload::make('images')
                                //      ->customProperties(fn (Get $get): array => [
                                //             'gallery_id' => $get('gallery_id'),
                                //             ])
                                //     ->filterMediaUsing(
                                //             fn (Collection $media, Get $get): Collection => $media->where(
                                //                 'custom_properties.gallery_id',
                                //                 $get('gallery_id')
                                //             ),
                                //         ),
                                // TextInput::make('cost')
                                //     ->prefix('€')
                                //     ->suffixAction(
                                //         Action::make('copyCostToPrice')
                                //             ->icon('heroicon-m-clipboard')
                                //             ->requiresConfirmation()
                                //             ->action(function (Set $set, $state) {
                                //                 $set('price', $state);
                                //             })
                                //         )->columnSpan(2),

                                // Action::make()
                                //     // ->model(ClienteDocumento::class)
                                //     ->form([
                                //         TextInput::make('title')
                                //             ->required()
                                //             ->maxLength(255),
                                //         // ...
                                //     ]),
                                Toggle::make('documento_completo')
                                    ->inline()
                                    ->live()
                                    ->onIcon('heroicon-o-document-check')
                                    ->offIcon('heroicon-o-document-minus')
                                    ->offColor('gray')
                                    ->columnSpan(3),
                                // Imagenes de cedulas y otros documentos
                                SpatieMediaLibraryFileUpload::make('imagen_doc')
                                    ->acceptedFileTypes(['image/gif', 'image/jpeg', 'image/png', 'image/tiff', 'image/webp ', 'image/avif', 'image/bmp' ])
                                    ->collection('ImagenesDocumentos')
                                    // ->visibility('private')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([  
                                            '17:11', 
                                            '16:9',  
                                            '4:3',
                                            ])
                                    ->multiple()
                                    ->responsiveImages()
                                    ->reorderable()
                                    // ->customProperties(fn (Get $get): array => [
                                    //     'cliente_id' => $get('cliente_id'),
                                    // ])
                                    ->filterMediaUsing(
                                        fn (Collection $media, Get $get): Collection => $media->where(
                                            'custom_properties.cliente_id',
                                            $get('cliente_id')
                                        ))
                                    ->columnSpan(4),
                                    // ->columnSpan(4),
                                Section::make('Imagen Documento')->schema([
                                    // The name is the relationship from Cliente Model clientedocumento hasMany function
                                   Repeater::make('clientedocumento')
                                       ->relationship()
                                    //    ->onsave(function (Cliente $cliente, Set $set) {
                                    //        $cliente->clientedocumento()->create($set->toArray());
                                    //    })
                                       ->schema([
                                    /*
                                        * tipo_documento needs to be changed for a Database field registered in the DB
                                        * or cast to an array either way it must be changed.
                                        */
                                        Group::make()
                                            ->schema([
                                                Select::make('documento_img')
                                                    ->live()
                                                    ->options(ImagenesDocumentoEnum::class),

                                        //             ->afterStateUpdated(function ($state, Set $set) {
                                        //                     $clienteDocumento = new ClienteDocumento;
                                        //                     // array_push($state, $clienteDocumento);
                                        //                     // Set any other necessary fields on $clienteDocumento here
                                        //                     $clienteDocumento->save([$state]);
                                        //                 }),
                                                    ]),
                                    // Select::make('documento_img')
                                    //     ->live()
                                    //     ->options(ImagenesDocumentoEnum::class)
                                        // ->afterStateUpdated(function (Request $request, Set $set) {
                                        //     $clienteDocumento = new ClienteDocumento;
                                        //     // Set any other necessary fields on $clienteDocumento here
                                        //     $clienteDocumento->save();
                                        // })
                                        // ->afterStateUpdated(function ($state, Set $set) {
                                        //     $clienteDocumento = new ClienteDocumento;
                                        //     // array_push($state, $clienteDocumento);
                                        //     // Set any other necessary fields on $clienteDocumento here
                                        //     $clienteDocumento->save([$state]);
                                        // })
                                        // ->columnSpan(4), // xxxxxxxxxxxxx

                                    // SpatieMediaLibraryFileUpload::make('imagen_doc') xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                                        // ->live()
                                        // ->visible(fn ($get) => $get('documento_img') !== null)
                                        // ->hiddenOn('documento_img')
                                        // ->acceptedFileTypes(['image/gif', 'image/jpeg', 'image/png', 'image/tiff', 'image/webp ', 'image/avif', 'image/bmp' ]) // xxxxxxxxxxxxx
                                        // ->visibility('private') // xxxxxxxxxxxxx
                                        // ->responsiveImages() // xxxxxxxxxxxxx
                                        // ->image()       // xxxxxxxxxxxxx
                                        // ->imageEditor() // xxxxxxxxxxxxx
                                        // ->imageEditorAspectRatios([  // xxxxxxxxxxxxx
                                        //     '17:11', // xxxxxxxxxxxxx
                                        //     '16:9',  // xxxxxxxxxxxxx
                                        //     '4:3',  // xxxxxxxxxxxxx
                                        // ]) // xxxxxxxxxxxxx
                                        // ->columnSpan(8)     // xxxxxxxxxxxxx
                                        // ->collection('ClienteDocumento'),
                                        // ->collection(fn (SpatieMediaLibraryFileUpload $component) => (string) str($component->getContainer()->getStatePath())->afterLast('.')),
                                        // ->collection('cliente_documentos'),
                                        
                                        //Repeater
                                       ])
                                        ->afterStateHydrated(null)
                                        ->mutateDehydratedStateUsing(null)
                                        ->grid(3)
                                        ->columnSpan(12)
                                        ->label('')
                                        ->addActionLabel('+ Documento'),

                                ])->columns(12),
                            //     Section::make()->schema([
                            //         SpatieMediaLibraryFileUpload::make('imagen_doc')
                            //         ->live()
                            //         ->visible(fn ($get) => $get('documento_img') !== null)
                            //         ->hiddenOn('documento_img')
                            //         ->acceptedFileTypes(['image/gif', 'image/jpeg', 'image/png', 'image/tiff', 'image/webp ', 'image/avif', 'image/bmp' ]) // xxxxxxxxxxxxx
                            //         ->visibility('private') // xxxxxxxxxxxxx
                            //         ->responsiveImages() // xxxxxxxxxxxxx
                            //         ->image()       // xxxxxxxxxxxxx
                            //         ->imageEditor() // xxxxxxxxxxxxx
                            //         ->imageEditorAspectRatios([  // xxxxxxxxxxxxx
                            //             '17:11', // xxxxxxxxxxxxx
                            //             '16:9',  // xxxxxxxxxxxxx
                            //             '4:3',  // xxxxxxxxxxxxx
                            //         ]) // xxxxxxxxxxxxx
                            //         ->columnSpan(8)     // xxxxxxxxxxxxx
                            //         ->collection('ClienteDocumento')
                            //         // ->saveRelationships('documentoclientes', 'imagen_doc'),
                            //     ])->label('Documentos'),
                            ])->columns(12)
                            ->icon('heroicon-o-camera'),
                    ])->columns(12)->columnSpan(12)->contained(false),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // Sort Newest to oldest
            ->columns([
                TextColumn::make('user.name')
                    ->label('Vendedor')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.usuario')
                    ->label('Usuario Vendedor')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tipo_documento')
                    ->label('Documento')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('documento')
                    ->label('#')
                    ->searchable(),
                TextColumn::make('primer_nombre')
                    ->label('Cliente')
                    ->formatStateUsing(function (string $state, Cliente $cliente) {
                        $NombreCompletoCliente = $cliente->primer_nombre.' '.$cliente->segundo_nombre.' '.$cliente->primer_apellido.' '.$cliente->segundo_apellido;
                        $Nombre = Str::squish($NombreCompletoCliente);

                        return $Nombre;
                    })
                    ->copyable()
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('segundo_nombre')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('primer_apellido')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('segundo_apellido')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('documento_completo')
                    ->label('Imagenes'),
                TextColumn::make('provincias.provincia')
                    ->sortable()
                    ->copyable(),
                TextColumn::make('cantones_id')
                    ->formatStateUsing(function (Cliente $client) {
                        $canton = Cantone::where('id_provincias', $client->provincias_id)
                            ->where('CantonNumber', $client->cantones_id)
                            ->value('canton'); // Assuming 'canton' is the column for the canton name

                        return $canton;

                    })
                    ->label('Canton')
                    ->copyable()
                    ->sortable(),

                TextColumn::make('distrito.distrito')
                    ->copyable()
                    ->sortable(),
                TextColumn::make('direccion')
                    ->html()
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('Vendedor')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('Provincia')
                    ->options(Provincia::all()->pluck('provincia', 'id'))
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
