<?php

namespace App\Filament\Resources;

//Resources
use App\Enums\EstatusVentaLineaEnum;
use App\Enums\PlanesLibertyLineasEnum;
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
// use Illuminate\Support\Facades\Cache;

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
            //
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
