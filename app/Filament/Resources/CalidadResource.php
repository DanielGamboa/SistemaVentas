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
use App\Enums\Calidad\LibertyNegativoEnum;

use Filament\Forms\Components\Repeater;
use App\Filament\Resources\CalidadResource\Pages;
use App\Filament\Resources\CalidadResource\RelationManagers;
use App\Models\Calidad;
use App\Models\VentaLinea;
use App\Models\User;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Tabs;
use App\Models\CalidadAuditoria;
use App\Models\CalidadAuditorium;
use Illuminate\Support\HtmlString;

// Cache
use Illuminate\Support\Facades\Cache;

//  filament\Actions\Action
// use Filament\Actions\Action;
//  Filament\Forms\Components\Actions\Action
// use Filament\Forms\Components\Actions\Action;
// Spatie media library


class CalidadResource extends Resource
{
    protected static ?string $model = Calidad::class;

    protected static ?string $navigationLabel = 'Calidad';

    protected static ?string $slug = 'calidad';

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    // Cache Calidad
    public static function retrieveRecords($request, $model)
    {
        return Cache::remember('calidads', 60 * 24 * 3, function () use ($model) {
            return $model::query()->get();
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                    ->schema([

//  ================================================================================  Start Motivo de La auditoria  ===================================================================================

                        Fieldset::make()
                            ->columns(12)
                            ->schema([

                                Select::make('motivo_evaluacion')
                                ->options(MotivoEvaluacionEnum::class)
                                ->enum(MotivoEvaluacionEnum::class)
                                ->live()
                                // If Venta is selected then this field will be visable and selectable
                                // Populated with VentasLinea drop down.
                                // If Agente, Aleatorio,  Gerencia, Solicitud Liberty, is selected then this field will be visable and selectable
                                // Populated with User drop down.
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $set('venta_lineas_id', null); //clears venta_lineas_id field on change
                                    if ($get('motivo_evaluacion') == 'Venta') {
                                        // $set('agente', null);
                                        $set('tlf', null);
                                        } else {
                                            $set('ventas_telefono', null);
                                            $set('tlf', null);
                                            }
                                        
                                    })
                                ->required()
                                // If Venta is selected then this field will be visable and selectable
                                // Populated with VentasLinea drop down.
                                ->columnSpan(3),
                                
//  ================================================================================  START AGENTE  & Tlf ===================================================================================


                        Select::make('agente')
                            ->relationship('user')
                            ->label('Seleccione el Agente')
                            ->live()
                            
                            ->visible(fn ($get) => 
                                    $get('motivo_evaluacion') == 'Agente' || 
                                    $get('motivo_evaluacion') == 'Aleatorio' || 
                                    $get('motivo_evaluacion') == 'Gerencia' || 
                                    $get('motivo_evaluacion') == 'Solicitud Liberty' ||
                                    // If Venta is selected then this field will be visable and selectable
                                    // $get('motivo_evaluacion') == 'Venta'
                                    ($get('ventas_telefono') !== '' && $get('ventas_telefono') !== null && $get('venta_lineas_id') !== '' && $get('venta_lineas_id') !== null) 
                                    
                                    )
                            // Dinamically populate agente select field with the agentes associated with the selected telefono venta VentaLinea or select from all users
                            ->options(
                                // function (Get $get, Set $set, $state) {

                                 fn (Get $get) => $get('ventas_telefono') 
                                    ? User::where('id', VentaLinea::find($get('ventas_telefono'))->user_id)->get()->pluck('name', 'id') 
                                    : User::all()->pluck('name', 'id')->toArray()

                                // if ($get('motivo_evaluacion') !== 'Venta') {
                                    // return fn (Get $get) => $get('ventas_telefono') 
                                    // ? User::where('id', VentaLinea::find($get('ventas_telefono'))->user_id)->get()->pluck('name', 'id') 
                                    // : User::all()->pluck('name', 'id');
                                    // dd(User::take(10)->pluck('name', 'id')->toArray());
                                    // return User::all()->pluck('name', 'id')->toArray();
                                // }
                                // If motivo_evaluacion is == venta Kahlua Kalua
                                // if ($get(('motivo_evaluacion == venta'))) {
                                    // return VentaLinea::all()->pluck('tlf', 'id');
                                    // $foo = $get('ventas_telefono')
                                    // return User::where('id', $state)->pluck('name', 'id')->toArray();
                                    // $foo = User::where('id', $state)->pluck('name', 'id');
                                    // return $foo;
                                    // }
                                    
                            ) 
                            // } )
                            // ->afterStateUpdated(function(Get $get){
                            //     if ($get('motivo_evaluacion') == 'Venta') {
                            //         User::where('id', VentaLinea::find($get('ventas_telefono'))->user_id)->get()->pluck('name', 'id');
                            //         }
                            //     })
                            // }
                                // fn (Get $get) => $get('ventas_telefono')
                                // User::where('id', VentaLinea::find($get('ventas_telefono'))->user_id)->get()->pluck('name', 'id')
                                // )
                            //         ? User::where('id', VentaLinea::find($get('ventas_telefono'))->user_id)->get()->pluck('name', 'id') 
                            //         : User::all()->pluck('name', 'id')->toArray()
                            // )
                            //
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => User::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                            ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->name)
                            // Make field disabled if motivo_evaluacion = venta
                            // ->disabled(fn ($get) => $get('motivo_evaluacion') == 'Venta')
                            
                            ->required()
                            ->columnSpan(6),


                        TextInput::make('tlf')
                            ->label('Teléfono')
                            ->live()
                            ->visible(Function (Get $get, Set $set) {
                                $ventasTelefono = $get('ventas_telefono');
                                $tlfMarcado = VentaLinea::where('id', $ventasTelefono)->value('tlf_marcado');
                                
                                // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                // dd($tlfMarcado);
                                // Get value from ventas_telefono, this value for the radio button is the VentasLineas ID
                                if (
                                    $get('motivo_evaluacion') == 'Agente' || 
                                    $get('motivo_evaluacion') == 'Aleatorio' || 
                                    $get('motivo_evaluacion') == 'Gerencia' || 
                                    $get('motivo_evaluacion') == 'Solicitud Liberty'
                                    
                                    // ($get('venta_telefono_marcado') !== null && $get('venta_lineas_id') !== null) ||
                                    // $tlfMarcado !== null 
                                    // // $tlfMarcado !== ''
                                    ) {
                                        // If not Venta it will come back true in order to manually populate
                                        return true;
                                    } else {
                                        // Nested if statement, in order to get the value from venta_lineas_id, if venta_lineas_id is null then return false 
                                        if ($get('venta_lineas_id') !== null && $get('ventas_telefono') !== null) { 
                                                return true;
                                        }
                                    }
                                })       
                                    // $get('venta_telefono_marcado') !== null
                                    
                                    // Evaluate if venta_telefono_marcado field is hidden
                                    
                            // Dinamically make field required if visible
                            ->required(fn ($get) => 
                                    $get('motivo_evaluacion') == 'Agente' || 
                                    $get('motivo_evaluacion') == 'Aleatorio' || 
                                    $get('motivo_evaluacion') == 'Gerencia' || 
                                    $get('motivo_evaluacion') == 'Solicitud Liberty'
                                    )
                            ->readOnly(function (Get $get, Set $set){
                                // $ventasTelefono = $get('ventas_telefono');
                                // $tlfMarcado = VentaLinea::where('id', $ventasTelefono)->value('tlf_marcado');
                                
                                // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                // return $ventaLinea && ($ventaLinea->tlf_marcado !== '' &&  $tlfMarcado !== '');
                                // return $tlfMarcado !== null;

                                if ($get('motivo_evaluacion') == 'Venta')
                                {

                                    return true;
                                }
                            })
                            ->columnSpan(3),


//  ================================================================================  END AGENTE  & Tlf ===================================================================================

//  ================================================================================  START VENTA ===================================================================================
                        Select::make('venta_lineas_id')
                            ->label('Teléfono Venta')
                            ->live()
                            ->searchable()
                            // ->visible(fn ($get) => $get('motivo_evaluacion') == 'Venta')
                            ->visible(function (Get $get, Set $set) {
                                if ($get('motivo_evaluacion') == 'Venta') {
                                    // If Venta is selected then this field will be visable and selectable
                                    // $set('tlf', null);
                                    return true;
                                    }

                                    // $set('agente', ''); //clears agente field on change
                                    $set('venta_lineas_id', ''); //clears venta_lineas_id field on change
                                    $set('ventas_telefono', null); //clears ventas_telefono field on change
                                    return false;
                            }) 
                            ->options(VentaLinea::all()->pluck('tlf', 'id'))
                            // AfterState updated if set to null or value changes clear ventas_telefono field on change
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                if ($get('motivo_evaluacion') !== 'Venta') {
                                    $set('ventas_telefono', null); //clears ventas_telefono field on change
                                    $set('venta_lineas_id', ''); //clears ventas_telefono field on change

                                    }
                                })

                                
                                // Posible point of error or conflict on state update.
                                // $set('ventas_telefono', null); //clears ventas_telefono field on change
                                
                            // make field required if visible
                            ->required(fn ($get) => $get('motivo_evaluacion') == 'Venta')
                            ->columnSpan(3),
//  ================================================================================  END VENTA ===================================================================================

                        // End of Fieldset Motivo de la auditoria
                            ]),


            
//  ================================================================================ Start Lista de ventas y datos aosciados ============================================================



//  ================================================================================  START VENTA ===================================================================================


                            Fieldset::make('Ventas')
                            ->schema([
                                Radio::make('ventas_telefono')
                                ->live()
                                // ->visible(fn ($get) => $get('motivo_evaluacion') == 'Venta')
                                ->options(function (Get $get) {
                                    if ($get('venta_lineas_id')) {
                                        // Fetch data based on the selected value (VentaLinea model or any other logic)
                                        // This will return the selected phone number not ID
                                            // $VentaLinea = VentaLinea::find($get('venta_lineas_id'));
                                            // dd($VentaLinea->clientes_id);
                                        // Get specific VentaLinea record clientes_id for asosiated Cliente record
                                            $VentaLinea = VentaLinea::where('id', $get('venta_lineas_id'))->value('clientes_id');
                                            // dd($dfoo);
                                            
                                            // $cliente = $VentaLinea->clientes_id;
                                        // Query the model based on the phone number
                                            // $records = VentaLinea::where('clientes_id', $cliente)->get();
                                            $records = VentaLinea::where('clientes_id', $VentaLinea)->get();
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
                                            $optionText =  $PlanesLibertyLineasStringValue. ' '. $PreciosPlanesLibertyNumberValueFormat. ' ' . $formattedDate ; //. ' ' . $record->plan. ' ' . $record->precio. ' ' . $record->created_at; // Adjust with your column names
                                        // $optionText = Str::squish($optionTextt);
                                        // Store as key-value pair in the options array
                                            $options[$record->id] = $optionText;  
                                            }
                                        return $options;
                                        }
                                    return ''; // Return an empty string if no value selected
                                    })
                                ->visible(function (Get $get) {
                                        // Get value from venta_lineas_id
                                        $ventaLinea = VentaLinea::find($get('venta_lineas_id'))->first();

                                        // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                        return $ventaLinea && ($ventaLinea !== '' or $ventaLinea !== null);
                                    })
                                // AfterState updated populate agente select field with the agentes associated with the selected telefono venta VentaLinea 
                                // ->options(User::all()->pluck('name', 'id'))
                                // ->afterStateUpdated(fn (Set $set, Get $get) => $set('agente', VentaLinea::find($get('ventas_telefono'))->user_id)),
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    // $set('agente', null); //clears agente field on change
                                    $set('tlf', ''); // clear tlf field on change
                                    $ventaLinea = VentaLinea::find($get('ventas_telefono'));
                                    if ($ventaLinea && $ventaLinea->user_id) {
                                        $user = User::find($ventaLinea->user_id);
                                        if ($user) {
                                            $set('agente', $user->id);
                                            }
                                        }
                                    // On specific sale selected populate tlf field with the phone number associated with the sale
                                    // If phone number sold is the same from the phone number called then populate tlf field with the phone number sold
                                    // because its the same as the phone diled.  
                                    // Make sure ventas_telefono exists.
                                    // Make sure tlf_marcado is null on selected item. Milka Milka
                                    if ($ventaLinea->tlf_marcado == null){
                                        
                                        $set('tlf', $ventaLinea->tlf);
                                        }
                                    
                                    // if ($ventaLinea && $ventaLinea->tlf_marcado == null) {
                                    //     $tlf = $get('ventas_telefono');
                                    //     $tlfVendido = VentaLinea::where('id', $tlf)->pluck('id', 'tlf');
                                    //     $set('tlf', $tlfVendido);
                                    //     $set('venta_lineas_id', $tlfVendido);
                                    //     // $set('ventas_telefono', VentaLinea::where('tlf', $tlfVendido)->value('id'));
                                    //     }
                                    }
                                    
                                ),
                                // ->afterStateUpdated(fn (Set $set, Get $get) => {
                                //     // Your code here
                                // }); // Add closing parenthesis and semicolon
                                //     $ventaLinea = VentaLinea::find($get('ventas_telefono'));
                                //     if ($ventaLinea && $ventaLinea->user_id) {
                                //         $user = User::find($ventaLinea->user_id);
                                //         if ($user) {
                                //             $set('agente', $user->name);
                                //         }
                                //     }
                                // }),


                            // En of Fieldset Ventas        
                                ])->columns(1)
                                ->columnSpan(6)
                                ->live()
                                 // Get value from venta_lineas_id
                                // $ventaLineasId = $get('venta_lineas_id');
                                // var_dump($ventaLineasId); // Debug line

                                // $ventaLinea = VentaLinea::find($ventaLineasId);
                                // var_dump($ventaLinea); // Debug line

                                // Check if $ventaLinea exists and tlf is not null or empty on specific VentaLinea record
                                // return $ventaLinea && ($ventaLinea->tlf !== '' or $ventaLinea->tlf !== null);
                                ->visible(function (Get $get) {
                                    
                                    // Get value from venta_lineas_id
                                    // $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                    // $ventaTlf = $ventaLinea['tlf'];
                                    // dd($ventaLinea['tlf']);
                                    // dd($ventaLinea); // Debug line
                                    // dd($ventaLinea['tlf']); // Debug line
                                    // $foo = $ventaLinea['tlf'];
                                    // Get value from venta_lineas_id
                                    $ventaLinea = VentaLinea::find($get('venta_lineas_id'));

                                    // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                    return $ventaLinea && ($ventaLinea !== '' or $ventaLinea !== null);
                                    // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                    // return $ventaLinea && ($ventaLinea->tlf !== '' or $ventaLinea->tlf !== null);
                                    // return $ventaLinea && ($ventaTlf !== '' or $ventaTlf !== null);
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
                                                $mefoo = $get('venta_lineas_id');
                                                $ventaLinea = VentaLinea::find($mefoo)->first();
                                                // $ventaLinea = VentaLinea::find($get('venta_lineas_id'));
                                                // dd($ventaLinea);
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
                                            return $ventaLinea && ($ventaLinea !== '' && $ventaLinea !== null);
                                        }),


                                Placeholder::make('TipoDocumento')
                                    ->label('Documento')
                                    ->inlineLabel()
                                    ->live()
                                    ->content(function (Get $get) {
                                        if ($get('venta_lineas_id')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'))->first();
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
                                            $ventaLinea = VentaLinea::find($get('venta_lineas_id'))->first();
                                            
                                            // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                            return $ventaLinea && ($ventaLinea->tlf !== '' && $ventaLinea->tlf !== null);
                                        }),
                                
                                // Insert here
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
                                    ->visible(function (Get $get) {
                                        // Get value from ventas_telefono this value for the radio button is the VentasLineas ID
                                        $ventasTelefono = $get('ventas_telefono');
                                        $tlfMarcado = VentaLinea::where('id', $ventasTelefono)->value('tlf_marcado');
                                        
                                        // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                        // return $ventaLinea && ($ventaLinea->tlf_marcado !== '' &&  $tlfMarcado !== '');
                                        return $tlfMarcado !== null;
                                        })
                                    ->content(function (Get $get, Set $set) {
                                        if ($get('ventas_telefono')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                                $ventasTelefono = $get('ventas_telefono');
                                                // $ventaLinea = VentaLinea::find($get('ventas_telefono'))->id;
                                            // Return the tlf_marcado associated with the selected ID
                                                $tlfMarcado = VentaLinea::where('id', $ventasTelefono)->value('tlf_marcado');
                                                // Kahlua
                                                $set('tlf', $tlfMarcado);
                                                // VentaLinea::where('id', $get('venta_lineas_id'))->value('clientes_id');
                                            return $ventasTelefono ? $tlfMarcado : '';
                                            }
                                        return ''; // Return an empty string if no value selected
                                        })
                                    ->inlineLabel(),
                                    //After state update use 'venta_telefono_marcado' value to populate 'tlf' field.
                                        
                                Placeholder::make('Dirección')
                                    ->inlineLabel()  // Might want to delete thes for this Placeholder
                                    ->live()
                                    ->content(function (Get $get) {
                                        if ($get('venta_lineas_id')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'))->first();
                                            // Get specific VentaLinea record clientes_id for asosiated Cliente record
                                                $cliente = $ventaLinea->clientes_id;
                                            // Query Cliente model for specific client and get their name.
                                                $clienteId = Cliente::find($cliente);
                                            // Return the tlf_marcado associated with the selected ID
                                                $Direccion = $clienteId->direccion;
                                                
                                            // Because the 'direccion' field is a text area and a rich text editor es being used DB will have HTML characters.
                                            // This will pass the HTML characters from the string and it will be rendered.
                                                return new HtmlString ($Direccion);
                                            // return $ventaLinea ? $ventaLinea->tlf_marcado : '';
                                            }
                                            return ''; // Return an empty string if no value selected
                                            })
                                    ->visible(function (Get $get) {
                                                // Get value from venta_lineas_id
                                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'))->first();
                                                
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
                                            $ventaLinea = VentaLinea::find($get('venta_lineas_id'))->first();
                                            
                                            // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                            return $ventaLinea && ($ventaLinea->tlf !== '' && $ventaLinea->tlf !== null);
                                        }),

                                Placeholder::make('Dirección entrega')
                                    ->inlineLabel()  // Might want to delete thes for this Placeholder
                                    ->live()
                                    ->visible(function (Get $get) {
                                        // Get value from ventas_telefono, this value for the radio button is the specific VentasLineas ID
                                        $ventasTelefono = $get('ventas_telefono');
                                        $direccionEntrega = VentaLinea::where('id', $ventasTelefono)->value('direccion_entrega');
                                        if($direccionEntrega !== null){
                                            return true;
                                        }
                                        // Check if $ventaLinea exists and direccion_entrega is not null on speficic VentaLinea record
                                        // return $ventaLinea && ($ventaLinea->direccion_entrega !== '' &&  $tlfMarcado !== '');
                                        // return $direccionEntrega !== '';
                                        })
                                    ->content(function (Get $get) {
                                        if ($get('ventas_telefono')) {

                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                            $ventasTelefono = $get('ventas_telefono');
                                            // Return the tlf_marcado associated with the selected ID
                                            $tlfMarcado = VentaLinea::where('id', $ventasTelefono)->value('direccion_entrega');
                                            // VentaLinea::where('id', $get('venta_lineas_id'))->value('clientes_id');
                                            return $ventasTelefono ? $tlfMarcado : '';
                                            }
                                        return ''; // Return an empty string if no value selected
                                        }),

                                Placeholder::make('ProvinciaCantonDistritoEntrega')
                                    ->label(' ')
                                    ->inlineLabel()
                                    ->live()
                                    ->content(function (Get $get) {
                                        if ($get('ventas_telefono')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                            // Get specific VentaLinea record clientes_id for asosiated Cliente record
                                                $ventaLinea = VentaLinea::find($get('ventas_telefono'));
                                            // Return the tlf_marcado associated with the selected ID
                                                $provincia = Provincia::where('id', $ventaLinea->provincias_id)->value('provincia');
                                                $canton = Cantone::where('id_provincias', $ventaLinea->provincias_id)->where('CantonNumber', $ventaLinea->cantones_id)->value('canton');
                                                $distrito = Distrito::where('id', $ventaLinea->distritos_id)->value('distrito');
                                            // Concatenate Provincia, Canton, Distrito.  In order to save space
                                            $ProvinciaCantonDistrito = $provincia. ', '. $canton. ', ' . $distrito;
                                            $Nombre = Str::squish($ProvinciaCantonDistrito);
                                            return $Nombre;
                                            }
                                        return ''; // Return an empty string if no value selected
                                        })
                                    ->visible(function (Get $get) {
                                            // Get value from ventas_telefono, this value for the radio button is the specific VentasLineas ID
                                            $ventasTelefono = $get('ventas_telefono');
                                            $direccionEntrega = VentaLinea::where('id', $ventasTelefono)->value('direccion_entrega');
                                            // Check if $ventaLinea exists and direccion_entrega is not null on speficic VentaLinea record
                                            return $direccionEntrega !== '';
                                        }),

                            //End of fieldset Datos del cliente y la venta
                            ])->columns(1)
                            ->columnSpan(6)
                            ->live()
                            ->visible(function (Get $get) {
                                // Get value from venta_lineas_id
                                $ventaLinea = VentaLinea::find($get('venta_lineas_id'));

                            
                                // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                // 
                                return $ventaLinea && ($ventaLinea !== '' or $ventaLinea !== null);
                                }),

                            // End of Fieldset            
                            ])->columns(12),



// ================================================================================= End Lista de ventas y datos aosciados ============================================================
// ================================================================================  End Motivo de la auditoria ===================================================================================




// ================================================================================== Start Fecha, hora y duración ===================================================================================
// Start of fieldset for Fecha, hora for the call in cuestion.
                        Fieldset::make('Fecha, hora y duración')
                        ->schema([
                            Repeater::make('grabaciones')
                                ->relationship('grabacionauditoria')
                                ->schema([
                                    FileUpload::make('grabacion')
                                        ->live()
                                        ->label('Grabación')
                                        ->acceptedFileTypes(['audio/wav', 'audio/webm', 'audio/aac', 'audio/mpeg', 'video/mp4', 'audio/mp4', 'audio/MP4A-LATM', 'audio/mp3', 'audio/ogg', 'audio/x-wav', 'audio/x-m4a', 'audio/x-mpeg',])
                                        ->storeFileNamesIn('original_filename')
                                        ->required()
                                        ->columnSpan(3),
                                    DatePicker::make('fecha_llamada')
                                        ->label('Fecha llamada (dd/mm/yyyy)')
                                        ->required()
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->columnSpan(3),
                                    TimePicker::make('dia_hora_inicio')
                                        ->required()
                                        ->live()
                                        ->columnSpan(2),
                                    TimePicker::make('dia_hora_final')
                                        ->required()
                                        ->live()
                                        ->columnSpan(2),
                                    // Get call duration from its starting and ending times, visible when not blank
                                    PlaceHolder::make('duracion')
                                        ->label('Duración')
                                        ->live()
                                        ->visible(function (Get $get) {
                                            // Get value from dia_hora_inicio & dia_hora_final
                                            $HoraInicio = $get('dia_hora_inicio');
                                            $HoraFinal = $get('dia_hora_final');
                                            // Check if $dia_hora_inicio and dia_hora_final are not null
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
                                            return 'Debe Ingresar una hora válida';
                                            })
                                        ->columnSpan(2),       
                                        
                            // End repeater for recording
                            ])
                            ->orderColumn('sort')
                            ->columns(12)
                            ->columnSpan(12)
                            // Mutate data in order to record duracion in H:i:s format between ending and starting time as well as calculate the duration of the call in seconds minutes and hours
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                // Set current user as the user_id
                                $data['user_id'] = auth()->id();
                                // Get value from dia_hora_inicio & dia_hora_final
                                $HoraInicio = $data['dia_hora_inicio'];
                                $HoraFinal = $data['dia_hora_final'];
                                // Check if $dia_hora_inicio and dia_hora_final are not null
                                if ($HoraInicio !== null && $HoraFinal !== null) {
                                    // Create Carbon instances for the starting and ending times
                                    $startTime = Carbon::createFromFormat('H:i:s', $HoraInicio);
                                    $endTime = Carbon::createFromFormat('H:i:s', $HoraFinal);
                                    // Calculate the time difference
                                    $timeDifference = $endTime->diff($startTime)->format('%H:%I:%S');
                                    // Set the value of the 'duracion' field to the calculated time difference
                                    $data['duracion'] = $timeDifference;
                                }

                                    $start = Carbon::parse($HoraInicio);
                                    $end = Carbon::parse($HoraFinal);
                                    $duration = $start->diffInSeconds($end);
                                    
                                    //Calculate the duration of the call in seconds minutes and hours
                                    $totalDuration = 0;
                                    $totalDuration += $duration;
                                    
                                   // Now we convert the total duration in seconds to hours, minutes and seconds
                                    $hours = floor($totalDuration / 3600);
                                    $minutes = floor(($totalDuration - ($hours * 3600)) / 60);
                                    $seconds = $totalDuration - ($hours * 3600) - ($minutes * 60);
                                    // $data['duracion'] = $hours . ':' . $minutes . ':' . $seconds;
                                    $data['durationseconds']= $totalDuration;
                                    $data['hours'] = $hours;
                                    $data['minutes'] = $minutes;
                                    $data['seconds'] = $seconds; 
                             
                                return $data;
                            })
                            // mutate data before update
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                // Set current user as the user_id
                                $data['user_id'] = auth()->id();
                                // Get value from dia_hora_inicio & dia_hora_final
                                $HoraInicio = $data['dia_hora_inicio'];
                                $HoraFinal = $data['dia_hora_final'];
                                // Check if $dia_hora_inicio and dia_hora_final are not null
                                if ($HoraInicio !== null && $HoraFinal !== null) {
                                    // Create Carbon instances for the starting and ending times
                                    $startTime = Carbon::createFromFormat('H:i:s', $HoraInicio);
                                    $endTime = Carbon::createFromFormat('H:i:s', $HoraFinal);
                                    // Calculate the time difference
                                    $timeDifference = $endTime->diff($startTime)->format('%H:%I:%S');
                                    // Set the value of the 'duracion' field to the calculated time difference
                                    $data['duracion'] = $timeDifference;
                                }

                                    $start = Carbon::parse($HoraInicio);
                                    $end = Carbon::parse($HoraFinal);
                                    $duration = $start->diffInSeconds($end);
                                    
                                    //Calculate the duration of the call in seconds minutes and hours
                                    $totalDuration = 0;
                                    $totalDuration += $duration;
                                    
                                   // Now we convert the total duration in seconds to hours, minutes and seconds
                                    $hours = floor($totalDuration / 3600);
                                    $minutes = floor(($totalDuration - ($hours * 3600)) / 60);
                                    $seconds = $totalDuration - ($hours * 3600) - ($minutes * 60);
                                    // $data['duracion'] = $hours . ':' . $minutes . ':' . $seconds;
                                    $data['durationseconds']= $totalDuration;
                                    $data['hours'] = $hours;
                                    $data['minutes'] = $minutes;
                                    $data['seconds'] = $seconds; 

                             
                                return $data;
                            }),
                            
                            // ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                    
                            //     $data['user_id'] = auth()->id();

                            //     $grabaciones = request()->input('grabaciones');
                            

                            // Remove the extra closing parenthesis
                        // }),
                        // Closing fieldset for Fecha, hora for the call in cuestion.    
                        // )),    
// ================================================================================== End Fecha, hora y duración ===================================================================================






// ================================================================================== Start Datos de la llamada ===================================================================================

                Fieldset::make('Datos de la llamada')
                    ->columns(12)
                    ->columnSpan(12)
                    ->schema([
                        // Generate nested tabs
                        Tabs::make('Tabs')
                            ->contained(false)
                            ->columns(12)
                            ->columnSpan(12)
                            ->tabs([
                                Tabs\Tab::make('Preventa')
                                    ->columns(12)
                                    ->columnSpan(12)
                                    ->schema([
                                    Tabs::make('SubTabs-1')
                                        ->columns(12)
                                        ->columnSpan(12)
                                        ->tabs([
                                            Tabs\Tab::make('SubTab-1-1')
                                            ->label('Bienvenida')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('bienvenida')
                                                    ->label('')
                                                    ->options(BienvenidaEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),

                                            Tabs\Tab::make('SubTab-1-2')
                                            ->label('Genera empatía')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('empatia')
                                                    ->label('')
                                                    ->options(EmpatiaEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),

                                            Tabs\Tab::make('SubTab-1-3')
                                            ->label('Sondeo')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('sondeo')
                                                    ->label('')
                                                    ->options(SondeoEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                        ])
                                    ]),
                                // Start de Tabs Venta + SubTabs
                                Tabs\Tab::make('Venta')
                                    ->columns(12)
                                    ->columnSpan(12)
                                    ->schema([
                                        Tabs::make('SubTabs')
                                        ->columns(12)
                                        ->columnSpan(12)
                                        ->tabs([
                                            Tabs\Tab::make('SubTab-2-1')
                                            ->label('Escucha activa')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('escucha_activa')
                                                    ->label('')
                                                    ->options(EscuchaActivaEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                            Tabs\Tab::make('SubTab-2-2')
                                            ->label('Oferta comercial')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('oferta_comercial')
                                                    ->label('')
                                                    ->options(OfertaComercialEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                            Tabs\Tab::make('SubTab-2-3')
                                            ->label('Número alternativo')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('numero_alternativo')
                                                    ->label('')
                                                    ->options(SolicitudNumeroAlternativoEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),

                                            Tabs\Tab::make('SubTab-2-4')
                                            ->label('Aclara dudas cliente')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('aclara_dudas_cliente')
                                                    ->label('')
                                                    ->options(AclaraDudasClienteEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                            
                                            Tabs\Tab::make('SubTab-2-5')
                                            ->label('Manejo de objeciones')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('manejo_objeciones')
                                                    ->label('')
                                                    ->options(ManejoObjecionesEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                            Tabs\Tab::make('SubTab-2-6')
                                            ->label('Ventas irregulares')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('genera_ventas_irregulares')
                                                    ->label('')
                                                    ->options(GenerarVentasIrregularesEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                        ])
                                    // End de Tabs Venta + SubTabs
                                    ]),
                                // Start Post Venta
                                Tabs\Tab::make('Post Venta')
                                    ->columns(12)
                                    ->columnSpan(12)
                                    ->schema([
                                        Tabs::make('SubTabs-3')
                                        ->columns(12)
                                        ->columnSpan(12)
                                        ->tabs([
                                            Tabs\Tab::make('SubTab-3-1')
                                            ->label('Aceptación del servicio')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('aceptacion_servicio')
                                                    ->label('')
                                                    ->options(AceptacionServicioEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),

                                            Tabs\Tab::make('SubTab-3-2')
                                            ->label('Técnicas de cierre')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('tecnicas_cierre')
                                                    ->label('')
                                                    ->options(TecnicasDeCierreVentasEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),

                                            Tabs\Tab::make('SubTab-3-3')
                                            ->label('Utiliza técnicas de cierre')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('utiliza_tecnicas_cierre')
                                                    ->label('')
                                                    ->options(UtilizaTecnicasCierreEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),

                                            Tabs\Tab::make('SubTab-3-4')
                                            ->label('Validación de venta')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('validacion_venta')
                                                    ->label('')
                                                    ->options(ValidacionVentaEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                        ])

                                    // End Post Venta
                                    ]),
                                
                                // Start Evaluacion del Agente tab
                                Tabs\Tab::make('Evaluacion del Agente')
                                    ->columns(12)
                                    ->columnSpan(12)
                                    ->schema([
                                        // ...
                                        Tabs::make('SubTabs-4')
                                        ->columns(12)
                                        ->columnSpan(12)
                                        ->tabs([
                                            Tabs\Tab::make('SubTab-4-1')
                                            ->label('Dicción')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('diccion')
                                                    ->label('Dicción, tono y volumen de voz')
                                                    ->options(DiccionEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                            
                                            Tabs\Tab::make('SubTab-4-2')
                                            ->label('Empatia')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('empatia_evalucion_agente')
                                                    ->label('Empatía:  Tambien se encuentra en Preventa => Empatia')
                                                    ->options(EmpatiaEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),

                                            Tabs\Tab::make('SubTab-4-3')
                                            ->label('Cliente en espera')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('espera_vacios')
                                                    ->label('Cliente en espera, uso de espacios vacios')
                                                    ->options(EsperaVaciosEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                            
                                            Tabs\Tab::make('SubTab-4-4')
                                            ->label('Escucha activa')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('escucha_activa_evaluacion_agente')
                                                    ->label('')
                                                    ->options(EscuchaActivaEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                            
                                            Tabs\Tab::make('SubTab-4-5')
                                            ->label('Evita maltrato al cliente')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('evita_maltrato')
                                                    ->label('')
                                                    ->options(EvitaMaltratoAlClienteEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                            
                                            Tabs\Tab::make('SubTab-4-6')
                                            ->label('Evita abandono llamada')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('abandono_llamada')
                                                    ->label('')
                                                    ->options(AbandonoLlamadaEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                                
                                            Tabs\Tab::make('SubTab-4-7')
                                            ->label('Defensa Liberty')
                                            ->columns(2)
                                            ->schema([
                                                CheckboxList::make('liberty_negativo')
                                                    ->label('')
                                                    ->options(LibertyNegativoEnum::class)
                                                    ->columns(2)
                                                    ->columnSpan(6),
                                                ]),
                                        // End of sub-tabs for evaluacion del agente tab
                                        ])
                                    // End Evaluacion del agente tab
                                    ]),
                            //  End of evaluacion de calidad tabs
                            ]),
// ================================================================================== End Datos de la llamada ===================================================================================
                    ]), 

// ================================================================================== End Datos de la llamada ===================================================================================
            

                Forms\Components\RichEditor::make('observaciones')
                    ->required()
                    ->columnSpan(12)
                    ->disableToolbarButtons([
                        'blockquote',
                        'strike',
                        'link',
                        'codeBlock',
                        'attachFiles',
                    ]),
                Forms\Components\Toggle::make('evaluacion_completa')
                    // ->required()
                    ->inline()
                    ->live()
                    ->onIcon('heroicon-o-document-check')
                    ->offIcon('heroicon-o-document-minus')
                    ->offColor('gray'),
                // End of Fieldset Datos de la llamada
                ]),

        ]);
        
    }
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // Sort Newest to oldest
            ->columns([
                // Get Auditor ID from user_id in the related User model.
                // This code makes use of Calidad model relationship with User model user() belongsTo.  This relationship is defined in the Calidad model.
                // Where 'user.name' --> 'user' is the relationship defined in the Calidad model and 'name' is the field in the User model.
                // So we pass user_id and return asosiated name.
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Auditor')
                    ->numeric()
                    ->sortable(),
                // Get user_id from CalidadAuditoria related model to Calidad model and get the name from the User model
                // The Agent column is the person who made the phone call that is being audited.
                // 
                // Este es el campo de  Vendedor o quien realizo la venta o la llamada
                Tables\Columns\TextColumn::make('agente')
                    ->label('Agente')
                    ->formatStateUsing(Function (Calidad $calidad){
                        // dd($calidad->agente;
                        $userId = $calidad->agente;
                        $user = User::where('id', $userId)->value('name');
                        return $user;
                        
                    })
                    ->sortable(),
                    // ->formatStateUsing(function (Calidad $calidad) {
                    //     dd($calidad->calidadauditoria->first()->user_id);
                    //     // dd($calidad->grabacionauditoria->first()->fecha_llamada);
                    //     // Get first date and from grabacion auditoria table and format the day from Y/m/d to d/m/Y
                    //     $auditor = $calidad->grabacionauditoria->first()->user_id;
                    //     dd($auditor);
                    //     return $auditor;
                    // }),
                // Tables\Columns\TextColumn::make('grabacionauditoria.user_id')
                //     ->label('Agente'),

                    // ->formatStateUsing(fn (Calidad $calidad) => $calidad->agente->name),
                Tables\Columns\TextColumn::make('motivo_evaluacion')
                    ->label('Motivo de la evaluación')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tlf')
                    ->label('Telefono')
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('VentaLinea.plan')
                Tables\Columns\TextColumn::make('ventas_telefono')
                    ->formatStateUsing(function (Calidad $calidad) {
                        // dd($calidad->ventas_telefono);
                        $ventasTelefono = $calidad->ventas_telefono;
                        $plan = VentaLinea::where('id', $ventasTelefono)->value('plan');
                        return $plan;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('grabacionauditoria.fecha_llamada')
                    ->label('Fecha primera llamada')
                    ->formatStateUsing(function (Calidad $calidad) {
                        // dd($calidad->grabacionauditoria->first()->fecha_llamada);
                        // Get first date and from grabacion auditoria table and format the day from Y/m/d to d/m/Y
                        $date = $calidad->grabacionauditoria->first()->fecha_llamada;
                        $date = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
                        return $date;
                    })
                    ->sortable(),
                // Tables\Columns\TextColumn::make('grabacionauditoria.dia_hora_inicio')
                    // ->label('Hora Inicio'),
                // Tables\Columns\TextColumn::make('grabacionauditoria.dia_hora_final')
                    // ->label('Hora Final'),
                Tables\Columns\TextColumn::make('id')
                    ->label('Duración')
                    // ===========================================   Test
                    ->formatStateUsing(function (Calidad $calidad) {
                        // dd($calidad->id);
                        $callRecordings = $calidad->id;
                        // Get the Calidad ID from the Calidad model to be used for the CalidadAuditoria model calidad_id in query.
                        // $callRecordings = $calidad->get('id', $calidad->id)->first()->value('id');
                        // dd($callRecordings);
                        
                        // Get the CalidadAuditoria model for the specific Calidad ID get all recrods with $callRecordings calidad_id
                        $calidad = CalidadAuditoria::where('calidad_id', $callRecordings)->get()->toArray();
                        // Collect and mat all the records from the CalidadAuditoria model for the specific Calidad ID & get all recrods with $callRecordings calidad_id
                        // Calculate the time interval for each record and return the sum of all the records
                        $durations = collect($calidad)->map(function ($record) {
                                $HoraInicio = Carbon::createFromFormat('H:i:s', $record['dia_hora_inicio']);
                                $HoraFinal = Carbon::createFromFormat('H:i:s', $record['dia_hora_final']);
                                return $HoraFinal->diffInSeconds($HoraInicio);
                            });
                        // Add all the records from the CalidadAuditoria model for the specific Calidad ID & get all recrods with $callRecordings calidad_id
                            $totalDurationInSeconds = $durations->sum();
                        // Format the total duration in seconds to hours, minutes, seconds 
                            $hours = floor($totalDurationInSeconds / 3600);
                            $minutes = floor(($totalDurationInSeconds / 60) % 60);
                            $seconds = $totalDurationInSeconds % 60;
                         // Format the total duration in seconds to hours, minutes, seconds   
                            $totalDurationString = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                         /// Return the total duration in hours, minutes, seconds   
                            return $totalDurationString;
                        }),
                       
                    // ===========================================   Test
                // Get all the records from the grabacionauditoria table and calculate the duration of the call
                    // ->formatState(function (Calidad $grabacionauditoria) {

                    //     $callRecordings = $grabacionauditoria->all();
                    //     $duracion = $callRecordings->map(function ($callRecording) {
                    //         $CalidadId = $callRecording->id;
                            
                    //         // We will use $CalidadId to querry the CalidadAuditoria model for the specific record
                    //         $calidad = CalidadAuditoria::where('calidad_id', $CalidadId)->get();
                    //         // Get value from dia_hora_inicio & dia_hora_final from CalidadAuditoria model
                    //         // Pluck the values from the collection for dia_hora_inicio & dia_hora_final
                    //         $HoraInicio = $calidad->pluck('dia_hora_inicio');
                    //         $HoraFinal = $calidad->pluck('dia_hora_final');

                    //         $HoraInicioCarbon = Carbon::createFromFormat('H:i:s', $HoraInicio->first());
                    //         $HoraFinalCarbon = Carbon::createFromFormat('H:i:s', $HoraFinal->first());
                    //         return $HoraFinalCarbon->diff($HoraInicioCarbon)->format('%H:%I:%S');

                    //     });
                    //     // dd($duracion);
                    //     return $duracion
                    // }),
                
                // Tables\Columns\TextColumn::make('motivo_evaluacion')
                //     ->searchable(),
                // Tables\Columns\IconColumn::make('evaluacion_completa')
                //     ->boolean(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter by deleted records
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar')->icon('heroicon-o-pencil'),
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
