<?php

namespace App\Filament\Resources;

// Enums
use App\Enums\TipoDocumentoEnum;
use App\Enums\Calidad\MotivoEvaluacionEnum;
// Enum Alphabeticaly
use App\Enums\Calidad\AbandonoLlamadaEnum;
use App\Enums\Calidad\AceptacionServicioEnum;
use App\Enums\Calidad\AclaraDudasClienteEnum;
use App\Enums\Calidad\BienvenidaEnum;
use App\Enums\Calidad\DiccionEnum;
use App\Enums\Calidad\EmpatiaEnum;
use App\Enums\Calidad\EscuchaActivaEnum;
use App\Enums\Calidad\EsperaVaciosEnum;
use App\Enums\Calidad\EvitaMaltratoAlClienteEnum;
use App\Enums\Calidad\GenerarVentasIrregularesEnum;
use App\Enums\Calidad\ManejoObjecionesEnum;
use App\Enums\Calidad\OfertaComercialEnum;
use App\Enums\Calidad\SolicitudNumeroAlternativoEnum;
use App\Enums\Calidad\SondeoEnum;
use App\Enums\Calidad\TecnicasDeCierreVentasEnum;
use App\Enums\Calidad\UtilizaTecnicasCierreEnum;
use App\Enums\Calidad\ValidacionVentaEnum;


use App\Filament\Resources\CalidadResource\Pages;
use App\Filament\Resources\CalidadResource\RelationManagers;
use App\Models\Calidad;
use App\Models\VentaLinea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\Cliente;
use App\Models\Provincia;
use App\Models\Cantone;
use App\Models\Distrito;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;


class CalidadResource extends Resource
{
    protected static ?string $model = Calidad::class;

    protected static ?string $navigationLabel = 'Calidad';

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Label')
                    ->schema([
                        // Motivo de la auditoria
                        Select::make('motivo_evaluacion')
                            ->options(MotivoEvaluacionEnum::class)
                            ->enum(MotivoEvaluacionEnum::class)
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('venta_lineas_id', null); //clears venta_lineas_id field on change
                                })
                            ->required()
                            // If Venta is selected then this field will be visable and selectable
                            // Populated with VentasLinea drop down.
                            ->columnSpan(6),
                        Select::make('venta_lineas_id')
                            ->label('Telefon Venta')
                            ->live()
                            ->searchable()
                            ->visible(fn ($get) => $get('motivo_evaluacion') == 'Venta')
                            ->options(VentaLinea::all()->pluck('tlf', 'id'))
                            ->required()
                            ->columnSpan(6),
                        Fieldset::make('Ventas')
                            ->schema([
                                Radio::make('ventas_telefono')
                                ->live()
                                // ->visible(fn ($get) => $get('motivo_evaluacion') == 'Venta')
                                ->options(function (Get $get) {
                                    if ($get('venta_lineas_id')) {
                                        // Fetch data based on the selected value (VentaLinea model or any other logic)
                                        // This will return the selected phone number not ID
                                            $VentaLinea = VentaLinea::find($get('venta_lineas_id'));
                                            $cliente = $VentaLinea->clientes_id;
                                         // Query the model based on the phone number
                                            $records = VentaLinea::where('clientes_id', $cliente)->get();
                                        // Generate options array for radio buttons with associated and concatenated data
                                            $options = [];
                                            
                                            foreach ($records as $record) {
                                        // Get specific VentaLinea record clientes_id for asosiated Cliente record
                                                $cliente = $record->clientes_id;
                                        // Get Client record based on client_id asosiated to the VentaLinea sale.
                                                $clienteId = Cliente::find($cliente);
                                        // Return the plan associated with the selected ID
                                            $PlanesLibertyLineasEnum = $record->plan;
                                        // Return enum plan value from VentaLinea model record to be passed to $optionText
                                            $PlanesLibertyLineasStringValue = $PlanesLibertyLineasEnum->value;
                                        // Return the price associated with the plan ID
                                            $PreciosPlanesLibertyEnum = $record->precio;
                                            
                                        // Return enum plan value from VentaLinea model record to be passed to $optionText
                                        $PreciosPlanesLibertyStringValue = $PreciosPlanesLibertyEnum;
                                        
                                        // Eddit String, add number format
                                        $PreciosPlanesLibertyNumberValueFormat = Number::format($PreciosPlanesLibertyStringValue);
                                        // Assuming $createdAt contains the 'created_at' date
                                            $createdAt = Carbon::parse($record->created_at);
                                            // Format the 'created_at' date to display as day, month, year
                                            $formattedDate = $createdAt->format('d-m-Y');
                                        // Concatenate associated data (e.g., Name, Document) for each record
                                            $optionText =  $record->id . ' ' . $PlanesLibertyLineasStringValue. ' '. $PreciosPlanesLibertyNumberValueFormat. ' ' . $formattedDate ; //. ' ' . $record->plan. ' ' . $record->precio. ' ' . $record->created_at; // Adjust with your column names
                                           // $optionText = Str::squish($optionTextt);
                                        // Store as key-value pair in the options array
                                            $options[$record->id] = $optionText;  
                                        }
                                    return $options;
                                    }
                                    // dd($options);// return 'foo'; // Return an empty string if no value selected
                                })->visible(function (Get $get) {
                                        // Get value from venta_lineas_id
                                        $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                        
                                        // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                        return $ventaLinea && ($ventaLinea->tlf !== '' or $ventaLinea->tlf !== null);
                                    }),
                        
                        
                            // En of Fieldset Ventas        
                                ])->columns(1)
                                  ->columnSpan(6)
                                  ->live()
                                  ->visible(function (Get $get) {
                                    // Get value from venta_lineas_id
                                    $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                    
                                    // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                    return $ventaLinea && ($ventaLinea->tlf !== '' or $ventaLinea->tlf !== null);
                                }),

                        // Start Fieldset Datos del cliente y la venta
                        Fieldset::make('Datos del Cliente y Venta')
                            ->schema([
                                Placeholder::make('NombreCliente')
                                    ->label('Nombre Cliente')
                                    ->inlineLabel()
                                    ->live()
                                    ->content(function (Get $get) {
                                        if ($get('venta_lineas_id')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                            // Get specific VentaLinea record clientes_id for asosiated Cliente record
                                                $cliente = $ventaLinea->clientes_id;
                                            // Query Cliente model for specific client and get their name.
                                                $clienteId = Cliente::find($cliente);
                                            // Return the tlf_marcado associated with the selected ID
                                            $NombreCompletoCliente = $clienteId->primer_nombre. ' ' . $clienteId->segundo_nombre. ' ' . $clienteId->primer_apellido. ' ' . $clienteId->segundo_apellido;
                                            $Nombre = Str::squish($NombreCompletoCliente);
                                            return $Nombre;
                                            }
                                        return ''; // Return an empty string if no value selected
                                        })
                                    ->visible(function (Get $get) {
                                            // Get value from venta_lineas_id
                                            $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                            
                                            // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                            return $ventaLinea && ($ventaLinea->tlf !== '' && $ventaLinea->tlf !== null);
                                        }),


                                Placeholder::make('TipoDocumento')
                                    ->label('Documento')
                                    ->inlineLabel()
                                    ->live()
                                    ->content(function (Get $get) {
                                        if ($get('venta_lineas_id')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                            // Get specific VentaLinea record clientes_id for asosiated Cliente record
                                                $cliente = $ventaLinea->clientes_id;
                                            // Query Cliente model for specific client and get their name.
                                                $clienteId = Cliente::find($cliente);
                                            // Return the tipo_documento associated with the selected ID
                                                $TipoDocumentoEnum = $clienteId->tipo_documento;
                                            // Return the documento associated with the selected ID
                                                $NumeroDocumento = $clienteId->documento;
                                            // Return enum TipoDocumentoEnum value from Cliente model record to be passed to $MatchDocumento
                                                $TipoDocumentoString = $TipoDocumentoEnum->value;
                                            // Define $MatchDocument in order to insert Enum value into string.
                                                $MatchDocument = match ($TipoDocumentoString) {
                                                    'Cedula' => TipoDocumentoEnum::Cedula,
                                                    'Dimex' => TipoDocumentoEnum::Dimex,
                                                    'Pasaporte' => TipoDocumentoEnum::Pasaporte,
                                                    'Refugiado' => TipoDocumentoEnum::Refugiado,
                                                    //default => null, // Handle default case or return a default value if needed
                                                };
                                             
                                                // Concatenate tipo_documento with documento in order to return both values in one line
                                                $result = (string) $MatchDocument->value . ' ' . $NumeroDocumento;
                                                    return $result; 
                                            }
                                        return ''; // Return an empty string if no value selected
                                        })
                                    ->visible(function (Get $get) {
                                            // Get value from venta_lineas_id
                                            $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                            
                                            // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                            return $ventaLinea && ($ventaLinea->tlf !== '' && $ventaLinea->tlf !== null);
                                        }),
                                
                                
                                Placeholder::make('venta_telefono_marcado')
                                    ->label('Telefono Marcado')
                                    // Edited Filament Resource file.  If filament does not fix this this will be overwriten on update
                                    // File path
                                    // C:\Users\dgamb\Documents\GlobalAxis\laravel\SistemaVentas\vendor\filament\forms\resources\views\components\placeholder.blade.php
                                    // added :has-inline-label="$hasInlineLabel()" after :label-sr-only="$isLabelHidden()"
                                    // comented out ->class(['fi-fo-placeholder sm:text-sm'])
                                    // added  ->class(['flex items-center justify-between text-sm gap-x-3 leading-6', 'sm:pt-1.5' => $hasInlineLabel() && (! $content instanceof \Illuminate\Contracts\Support\Htmlable),])
                                    // CSS is not perfict but its a good aproximation.
                                    // track on braking changes
                                    // Daniel Gamboa
                                    
                                    ->live()
                                    ->content(function (Get $get) {
                                        if ($get('venta_lineas_id')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'));

                                            // Return the tlf_marcado associated with the selected ID
                                            return $ventaLinea ? $ventaLinea->tlf_marcado : '';
                                            }
                                        return ''; // Return an empty string if no value selected
                                        })
                                        ->inlineLabel()
                                    ->visible(function (Get $get) {
                                            // Get value from venta_lineas_id
                                            $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                            
                                            // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                            return $ventaLinea && ($ventaLinea->tlf_marcado !== '' && $ventaLinea->tlf_marcado !== null);
                                        }),



                                Placeholder::make('Dirección')
                                    ->inlineLabel()  // Might want to delete thes for this Placeholder
                                    ->live()
                                    ->content(function (Get $get) {
                                        if ($get('venta_lineas_id')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                            // Get specific VentaLinea record clientes_id for asosiated Cliente record
                                                $cliente = $ventaLinea->clientes_id;
                                            // Query Cliente model for specific client and get their name.
                                                $clienteId = Cliente::find($cliente);
                                            // Return the tlf_marcado associated with the selected ID
                                                $Direccion = $clienteId->direccion;
                                            // $Nombre = Str::squish($NombreCompletoCliente);
                                                return $Direccion;
                                            // return $ventaLinea ? $ventaLinea->tlf_marcado : '';
                                            }
                                            return ''; // Return an empty string if no value selected
                                            })
                                    ->visible(function (Get $get) {
                                                // Get value from venta_lineas_id
                                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                                
                                                // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                                return $ventaLinea && ($ventaLinea->tlf !== '' && $ventaLinea->tlf !== null);
                                            }),
                
                                Placeholder::make('ProvinciaCantonDistrito')
                                    ->label(' ')
                                    ->inlineLabel()
                                    ->live()
                                    ->content(function (Get $get) {
                                        if ($get('venta_lineas_id')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                            // Get specific VentaLinea record clientes_id for asosiated Cliente record
                                                $cliente = VentaLinea::where('id', $get('venta_lineas_id'))->value('clientes_id');
                                            // Query Cliente model for specific client and get their name.
                                                $clienteId = Cliente::find($cliente);
                                            // Return the tlf_marcado associated with the selected ID
                                                $provincia = Provincia::where('id', $clienteId->provincias_id)->value('provincia');
                                                $canton = Cantone::where('id_provincias', $clienteId->provincias_id)->where('CantonNumber', $clienteId->cantones_id)->value('canton');
                                                $distrito = Distrito::where('id', $clienteId->distritos_id)->value('distrito');
                                            // Concatenate Provincia, Canton, Distrito.  In order to save space
                                            $ProvinciaCantonDistrito = $provincia. ', '. $canton. ', ' . $distrito;
                                            $Nombre = Str::squish($ProvinciaCantonDistrito);
                                            return $Nombre;
                                            }
                                        return ''; // Return an empty string if no value selected
                                        })
                                    ->visible(function (Get $get) {
                                            // Get value from venta_lineas_id
                                            $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                            
                                            // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                            return $ventaLinea && ($ventaLinea->tlf !== '' && $ventaLinea->tlf !== null);
                                        }),


                        //End of fieldset Datos del cliente y la venta
                            ])->columns(1)
                              ->columnSpan(6)
                              ->live()
                              ->visible(function (Get $get) {
                                // Get value from venta_lineas_id
                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                              
                                // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                return $ventaLinea && ($ventaLinea->tlf !== '' or $ventaLinea->tlf !== null);
                                }),

            // End of Fieldset            
                    ])->columns(12),


            // Start of fieldset for Fecha, hora for the call in cuestion.    
                Fieldset::make('Fecha, hora y duración')
                            ->schema([
                                DatePicker::make('fecha_llamada')
                                    ->label('Fecha llamada (dd/mm/yyyy)')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y'),
                                TimePicker::make('dia_hora_inicio')
                                    ->required()
                                    ->live(),
                                TimePicker::make('dia_hora_final')
                                    ->required()
                                    ->live(),
                            
                                PlaceHolder::make('duracion')
                                    ->label('Duración')
                                    ->live()
                                    ->visible(function (Get $get) {
                                        // Get value from venta_lineas_id
                                        $HoraInicio = $get('dia_hora_inicio');
                                        $HoraFinal = $get('dia_hora_final');
                                        
                                        // dd($HoraInicio);
                                        // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                        return ($HoraInicio !== null && $HoraFinal !== null);
                                    })
                                    ->content(function (Get $get) {
                                        if ($get('dia_hora_inicio') !== '' && $get('dia_hora_final') !== '') {
                                            $HoraInicio = $get('dia_hora_inicio');
                                            $HoraFinal = $get('dia_hora_final');

                                            // Create Carbon instances for the starting and ending times
                                            $startTime = Carbon::createFromFormat('H:i:s', $HoraInicio);
                                            $endTime = Carbon::createFromFormat('H:i:s', $HoraFinal);
                                            // Calculate the time difference
                                            $timeDifference = $endTime->diff($startTime)->format('%H:%I:%S');

                                            // Set the value of the 'duracion' field to the calculated time difference
                                            return $timeDifference;

                                        }
                                        return 'Debe Ingresar una hora valida';
                                    })
                                
                                    

                            ]),    

                Forms\Components\Textarea::make('observaciones')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('evaluacion_completa')
                    ->required(),
                Forms\Components\TextInput::make('bienvenida')
                    ->required(),
                Forms\Components\TextInput::make('empatia')
                    ->required(),
                Forms\Components\TextInput::make('diccion')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venta_lineas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_llamada')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dia_hora_inicio'),
                Tables\Columns\TextColumn::make('dia_hora_final'),
                Tables\Columns\TextColumn::make('motivo_evaluacion')
                    ->searchable(),
                Tables\Columns\IconColumn::make('evaluacion_completa')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListCalidads::route('/'),
            'create' => Pages\CreateCalidad::route('/create'),
            'edit' => Pages\EditCalidad::route('/{record}/edit'),
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
