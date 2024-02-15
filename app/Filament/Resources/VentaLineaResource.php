<?php

namespace App\Filament\Resources;

//Resources
use App\Enums\EstatusVentaLineaEnum;
use App\Enums\PlanesLibertyLineasEnum;
use App\Enums\TipoDocumentoEnum;
// Models
use App\Enums\PreciosPlanesLibertyLineasEnum;
use App\Enums\VentaLineasEnum;
use App\Filament\Resources\VentaLineaResource\Pages;
use App\Models\Cantone;
use App\Models\Cliente;
// Forms
use App\Models\Distrito;
use App\Models\Provincia;
use App\Models\VentaLinea;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
// Tables
use Filament\Forms\Form;
use Filament\Forms\Get;
// Eloquent
use Filament\Forms\Set;
// Enums
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

// WhatsApp Logo Convert HtmlString And align img to center
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\Alignment;

// Add relationship
use App\Filament\Resources\VentaLineaResource\RelationManagers\NumeroReferenciasRelationManager;

// Add Widgets
use App\Filament\Resources\VentaLineaResource\Widgets\StatsOverview;

use Illuminate\Database\Eloquent\Builder;

class VentaLineaResource extends Resource
{
    protected static ?string $model = VentaLinea::class;

    // Menu lataral desplegable -- Concepto Ventas
    // protected static ?string $navigationGroup = 'Registrar Ventas';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('clientes_id')
                        ->label('Cliente')
                        ->required()
                        ->options(Cliente::all()->pluck('documento_nombre_completo', 'id'))
                        // ->options(Cliente::all()->pluck('documento', 'id'))
                        ->searchable(['documento', 'primer_nombre', 'primer_apellido']),

                ])->columnSpan(6),

                Section::make()->schema([
                    TextInput::make('tlf')
                        ->label('Teléfono')
                        ->length(8)
                        ->validationAttribute('Solo se acceptan numero, sin caracteres ( ) - /')
                        ->required()
                        ->columnSpan(4),
                    Toggle::make('tlf_venta_distinto')
                        ->label('Telefono de contacto es distinto')
                        // ->inline(false)
                        ->live()
                        ->onIcon('heroicon-m-phone')
                        ->offIcon('heroicon-m-phone')
                        ->offColor('gray')
                        ->columnSpan(4),
                    TextInput::make('tlf_marcado')
                        ->label('Telefono Marcado')
                        ->visible(fn (Get $get): bool => $get('tlf_venta_distinto'))
                        ->columnSpan(4),

                ])->columns(12)->columnSpan(6),
                // I add Registered user with \VentaLineaResource\Page Create with mutateFormDataBeforeCreate
                // Rather than adding them on the form directly.
                Section::make()->schema([
                    Select::make('VentaLinea')
                        ->options(VentaLineasEnum::class)
                        ->enum(VentaLineasEnum::class)
                        ->required()
                        ->columnSpan(4),
                    Select::make('plan')
                        ->options(PlanesLibertyLineasEnum::class)
                        ->live()
                        ->afterStateUpdated(function (Set $set, $state) {
                            // Set: is a built in function that takes two parameters
                            // $state is the retrived value in this case the PlanesLibertyLineasEnum value (not key)
                            $PlanLiberty = Str::replace(' ', '', $state);

                            $PrecioLibery = match ($PlanLiberty) {
                                // $PlanLiberty takes the @ from the value for PlanesLibertyLineasEnum::class
                                // Not the key that does not have the @ in its name.
                                'Plan@1' => PreciosPlanesLibertyLineasEnum::Plan1,
                                'Plan@1Pluss' => PreciosPlanesLibertyLineasEnum::Plan1Pluss,
                                'Plan@2' => PreciosPlanesLibertyLineasEnum::Plan2,
                                'Plan@3' => PreciosPlanesLibertyLineasEnum::Plan3,
                                'Plan@4' => PreciosPlanesLibertyLineasEnum::Plan4,
                                'Plan@5' => PreciosPlanesLibertyLineasEnum::Plan5,
                                'Plan@6' => PreciosPlanesLibertyLineasEnum::Plan6,

                                default => null,
                            };

                            $set('precio', $PrecioLibery);
                        })
                        ->required()
                        ->columnSpan(4),
                    TextInput::make('precio')
                        ->readOnly()
                        ->required()
                        ->columnSpan(4),
                ])->columns(12)->columnSpan(12),

                Select::make('Estatus')
                    ->options(EstatusVentaLineaEnum::class)
                    ->enum(EstatusVentaLineaEnum::class)
                    ->required()
                    ->default('Evaluación Crediticia')
                    ->columnSpan(3),

                //
                Forms\Components\Toggle::make('entrega_distinta')
                    ->label('Dirección de entrega es distinta a la de facturación')
                    ->onIcon('heroicon-m-map')
                    ->offIcon('heroicon-m-map')
                    ->offColor('gray')
                    ->required()
                    ->live()
                    ->columnSpan(4),
                //
                Section::make()->schema([
                    // Direccion de facturación
                    RichEditor::make('direccion_entrega')
                        ->label('Dirección de Entrega')
                        ->disableToolbarButtons([
                        'blockquote',
                        'strike',
                        'link',
                        'codeBlock',
                        'attachFiles',
                        ]),

                ])->columnSpan(6)->visible(fn (Get $get): bool => $get('entrega_distinta')),
                Section::make()->schema([
                    //
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
                        // ->pluck('canton', 'CantonNumber')
                        // )
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
                        //     ->where('provincias_id', $get('provincias_id'))
                        //     ->where('cantones_id', $get('cantones_id'))
                        //     ->pluck('distrito', 'id')
                        // )
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
                ])->columnSpan(6)->visible(fn (Get $get): bool => $get('entrega_distinta')),

                // copy paste

                //

            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // Sorts the table by the created_at column in descending order
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('WhatsApp')
                    ->formatStateUsing(function($record):HTMLString {
                        return new HtmlString('<img src="/images/svg/whatsapp.svg" alt="WhatsApp" class="w-6 h-6" />');
                    })
                    ->alignment(Alignment::Center)
                    ->copyable()
                    ->copyableState(function (VentaLinea $record) {
                        $UserName = $record->user->name;

                        // Get Client related record from VentaLinea record --> $cliente will get the related record row on Clientes table.
                        $cliente = $record->cliente;
                        // Get Client from Cliente model and concatenate the full name
                        $NombreCompletoCliente = $cliente->primer_nombre.' '.$cliente->segundo_nombre.' '.$cliente->primer_apellido.' '.$cliente->segundo_apellido;
                        $Nombre = Str::squish($NombreCompletoCliente);

                        // Convert EstatusVentaLineaEnum to a string
                        $Estatus = $record->Estatus->value;
                        // dd($Estatus);
                        // Convert VentaLineasEnum to a string
                        $VentaLineas = $record->VentaLinea->value;
                        
                        // Convert TipoDocumentoEnum to a string
                        $tipoDocumento = $cliente->tipo_documento->value;
                        
                        // Convert PlanesLibertyLineasEnum to a string
                        $PlanesLiberty = $record->plan->value; 
                        
                        // Get the creation date and time
                        $creationDateTime = $record->created_at;
                        // Format the date and time
                        $date = $creationDateTime->format('d/m/Y');
                        $time = $creationDateTime->format('H:i:s');

                        // Check if entrega_distinta is true or false  if true get record direccion_entrega if false get cliente direccion
                        if ($record->entrega_distinta) {
                            // Get the direccion_entrega from the record
                            $DireccionEntrega = $record->direccion_entrega;
                            // Get provincia, canton and distrito from the related record on the table.  Chip delivery direction
                            $provincia = $record->provincias->provincia;
                            $canton = $record->cantona->where('id_provincias', $record->provincias_id)->where('CantonNumber', $record->cantones_id)->first()->canton;
                            $distrito = $record->distrito->distrito;
                        } else {
                            $DireccionEntrega = $cliente->direccion;
                            $provincia = $cliente->provincias->provincia;
                            $canton = $cliente->cantona->where('id_provincias', $cliente->provincias_id)->where('CantonNumber', $cliente->cantones_id)->first()->canton;
                            $distrito = $cliente->distrito->distrito;
    
                        }

                        // Number to be ported is the same as the record tlf if tlf_venta_distinto is false and $VentaLineas == Portabilidad
                        if ($VentaLineas == 'Portabilidad') {
                            $NumeroPortar = $record->tlf;
                        } else {
                            $NumeroPortar = 'No aplica';
                        }
                        $Referencia = '';
                        // Number of the call is tlf_marcado if tlf_venta_distinto is true els tlf
                        if ($record->tlf_venta_distinto) {
                            $NumeroLlamada = $record->tlf_marcado;
                            $Referencia = $record->tlf;
                        } else {
                            $NumeroLlamada = $record->tlf;
                        }

                        return "*DTS:* Global Axis\n*Vendedor:* $UserName\n*Codigo de Vendedor:* $record->user_id\n*Canal:* Televentas\n*Status de la venta:* $Estatus\n\n*Nombre del cliente:* $Nombre\n*Numero de $tipoDocumento:* $cliente->documento\n*Numero a portar:* $NumeroPortar\n*Tipo de Venta:* $VentaLineas\n*Numero de la llamada:* $NumeroLlamada\n\n*Direccion Entrega:* $DireccionEntrega\n\n*Provincia:* $provincia\n*Canton:* $canton\n*Distrito:* $distrito\n\n*Correo: $cliente->email*\n*Numero de Referencia:*\n*1.* $Referencia\n*Plan:* $PlanesLiberty\n*Comentario:*\n*Hora venta:* $time\n*Fecha:* $date";


                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Vendedor')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cliente.tipo_documento')
                    ->label('Documento')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cliente.documento')
                    ->label('#')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cliente.primer_nombre')
                    ->formatStateUsing(function ($state, VentaLinea $cliente) {
                        $NombreCompletoCliente = $cliente->cliente->primer_nombre.' '.$cliente->cliente->segundo_nombre.' '.$cliente->cliente->primer_apellido.' '.$cliente->cliente->segundo_apellido;
                        $Nombre = Str::squish($NombreCompletoCliente);

                        return $Nombre;
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cliente.segundo_nombre')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cliente.primer_apellido')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cliente.segundo_apellido')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('VentaLinea')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tlf')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('plan')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('precio')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Estatus')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(now())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(now())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add NumeroReferenciasRelationManager to the VentaLineaResource
            NumeroReferenciasRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            StatsOverview::class,
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentaLineas::route('/'),
            'create' => Pages\CreateVentaLinea::route('/create'),
            'edit' => Pages\EditVentaLinea::route('/{record}/edit'),
        ];
    }
}
