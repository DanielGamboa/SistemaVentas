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
use App\Models\CalidadAuditoria;
use App\Models\CalidadAuditorium;
use Illuminate\Support\HtmlString;

//  filament\Actions\Action
// use Filament\Actions\Action;
//  Filament\Forms\Components\Actions\Action
// use Filament\Forms\Components\Actions\Action;
// Spatie media library


class CalidadResource extends Resource
{
    protected static ?string $model = Calidad::class;

    protected static ?string $navigationLabel = 'Calidad';

    protected static ?string $navigationIcon = 'heroicon-o-scale';

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
                                        $set('agente', null);
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
                            ->searchable()
                            ->visible(fn ($get) => 
                                    $get('motivo_evaluacion') == 'Agente' || 
                                    $get('motivo_evaluacion') == 'Aleatorio' || 
                                    $get('motivo_evaluacion') == 'Gerencia' || 
                                    $get('motivo_evaluacion') == 'Solicitud Liberty' ||
                                    $get('ventas_telefono') !== null
                                    )
                            // Dinamically populate agente select field with the agentes associated with the selected telefono venta VentaLinea or select from all users
                            ->options(fn (Get $get) => $get('ventas_telefono') ? User::where('id', VentaLinea::find($get('ventas_telefono'))->user_id)->get()->pluck('name', 'id') : User::all()->pluck('name', 'id'))

                            
                            // AfterState updated if set to null and ventas_telefono is not null set agente to the agentes associated with the selected telefono venta VentaLinea
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                if ($get('ventas_telefono') !== null) {
                                    $set('agente', VentaLinea::find($get('ventas_telefono'))->user_id);
                                    }
                                })
                            ->required()
                            ->columnSpan(6),


                        TextInput::make('tlf')
                            ->label('Teléfono')
                            ->live()
                            ->visible(fn ($get) => 
                                    $get('motivo_evaluacion') == 'Agente' || 
                                    $get('motivo_evaluacion') == 'Aleatorio' || 
                                    $get('motivo_evaluacion') == 'Gerencia' || 
                                    $get('motivo_evaluacion') == 'Solicitud Liberty'
                                    )
                            // Dinamically make field required if visible
                            ->required(fn ($get) => 
                                    $get('motivo_evaluacion') == 'Agente' || 
                                    $get('motivo_evaluacion') == 'Aleatorio' || 
                                    $get('motivo_evaluacion') == 'Gerencia' || 
                                    $get('motivo_evaluacion') == 'Solicitud Liberty'
                                    )
                            ->columnSpan(3),


//  ================================================================================  END AGENTE  & Tlf ===================================================================================

//  ================================================================================  START VENTA ===================================================================================
                        Select::make('venta_lineas_id')
                            ->label('Teléfono Venta')
                            ->live()
                            ->searchable()
                            ->visible(fn ($get) => $get('motivo_evaluacion') == 'Venta')
                            ->options(VentaLinea::all()->pluck('tlf', 'id'))
                            // AfterState updated if set to null or value changes clear ventas_telefono field on change
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $set('ventas_telefono', null); //clears ventas_telefono field on change
                                })
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
                                        $ventaLinea = VentaLinea::find($get('venta_lineas_id'));

                                        // Check if $ventaLinea exists and tlf_marcado is not null or empty on speficic VentaLinea record
                                        return $ventaLinea && ($ventaLinea->tlf !== '' or $ventaLinea->tlf !== null);
                                    })
                                // AfterState updated populate agente select field with the agentes associated with the selected telefono venta VentaLinea 
                                // ->options(User::all()->pluck('name', 'id'))
                                ->afterStateUpdated(fn (Set $set, Get $get) => $set('agente', VentaLinea::find($get('ventas_telefono'))->user_id)),
                                


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
                                    ->content(function (Get $get) {
                                        if ($get('ventas_telefono')) {
                                            // Fetch data based on the selected value (VentaLinea model or any other logic)
                                                $ventasTelefono = $get('ventas_telefono');
                                                // $ventaLinea = VentaLinea::find($get('ventas_telefono'))->id;
                                            // Return the tlf_marcado associated with the selected ID
                                                $tlfMarcado = VentaLinea::where('id', $ventasTelefono)->value('tlf_marcado');
                                                // VentaLinea::where('id', $get('venta_lineas_id'))->value('clientes_id');
                                            return $ventasTelefono ? $tlfMarcado : '';
                                            }
                                        return ''; // Return an empty string if no value selected
                                        })
                                        ->inlineLabel(),

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
                                                
                                            // Because the 'direccion' field is a text area and a rich text editor es being used DB will have HTML characters.
                                            // This will pass the HTML characters from the string and it will be rendered.
                                                return new HtmlString ($Direccion);
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
                                return $ventaLinea && ($ventaLinea->tlf !== '' or $ventaLinea->tlf !== null);
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
                            ->columns(12)
                            ->columnSpan(12)
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                    
                                $data['user_id'] = auth()->id();

                                $grabaciones = request()->input('grabaciones');
                            }),

                                // // Get value from dia_hora_inicio & dia_hora_final
                                // $HoraInicio = $data['dia_hora_inicio'];
                                // $HoraFinal = $data['dia_hora_final'];
                                // // Check if $dia_hora_inicio and dia_hora_final are not null
                                // if ($HoraInicio !== null && $HoraFinal !== null) {
                                //     // Create Carbon instances for the starting and ending times
                                //     $startTime = Carbon::createFromFormat('H:i:s', $HoraInicio);
                                //     $endTime = Carbon::createFromFormat('H:i:s', $HoraFinal);
                                //     // Calculate the time difference
                                //     $timeDifference = $endTime->diff($startTime)->format('%H:%I:%S');
                                //     // Set the value of the 'duracion' field to the calculated time difference
                                //     $data['duracion'] = $timeDifference;
                                // }
                                // dd($data);
                                

                            // Remove the extra closing parenthesis
                        // }),
                        // Closing fieldset for Fecha, hora for the call in cuestion.    
                        // )),    
// ================================================================================== End Fecha, hora y duración ===================================================================================






// ================================================================================== Start Datos de la llamada ===================================================================================

                Fieldset::make('Datos de la llamada')
                    ->columns(12)
                    ->schema([
                        Select::make('bienvenida')
                            ->live()
                            ->options(BienvenidaEnum::class)
                            ->enum(BienvenidaEnum::class)
                            // ->multiple()
                            ->columnSpan(3),
                        Select::make('abandono_llamada')
                            ->live()
                            ->options(AbandonoLlamadaEnum::class)
                            ->enum(AbandonoLlamadaEnum::class)
                            ->columnSpan(3),
                        Select::make('sondeo')
                            ->live()
                            ->options(SondeoEnum::class)
                            ->enum(SondeoEnum::class)
                            ->columnSpan(3),
                        Select::make('aclara_dudas_cliente')
                            ->live()
                            ->options(AclaraDudasClienteEnum::class)
                            ->enum(AclaraDudasClienteEnum::class)
                            ->columnSpan(3),
                        Select::make('espera_vacios')
                            ->live()
                            ->options(EsperaVaciosEnum::class)
                            ->enum(EsperaVaciosEnum::class)
                            ->columnSpan(3),
                        Select::make('escucha_activa')
                            ->live()
                            ->options(EscuchaActivaEnum::class)
                            ->enum(EscuchaActivaEnum::class)
                            ->columnSpan(3),
                        Select::make('empatia')
                            ->live()
                            ->options(EmpatiaEnum::class)
                            ->enum(EmpatiaEnum::class)
                            ->columnSpan(3),
                        Select::make('evita_maltrato_al_cliente')
                            ->live()
                            ->options(EvitaMaltratoAlClienteEnum::class)
                            ->enum(EvitaMaltratoAlClienteEnum::class)
                            ->columnSpan(3),
                        Select::make('diccion')
                            ->live()
                            ->options(DiccionEnum::class)
                            ->enum(DiccionEnum::class)
                            ->columnSpan(3),
                        Select::make('utiliza_tecnicas_cierre')
                            ->live()
                            ->options(UtilizaTecnicasCierreEnum::class)
                            ->enum(UtilizaTecnicasCierreEnum::class)
                            ->columnSpan(3),
                        Select::make('tecnicas_de_cierre_ventas')
                            ->live()
                            ->options(TecnicasDeCierreVentasEnum::class)
                            ->enum(TecnicasDeCierreVentasEnum::class)
                            ->columnSpan(3),
                    ]), 

// ================================================================================== End Datos de la llamada ===================================================================================
            


                Forms\Components\Textarea::make('observaciones')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                // Forms\Components\Toggle::make('evaluacion_completa')
                //     ->required(),

                // Forms\Components\TextInput::make('bienvenida')
                //     ->required(),
                // Forms\Components\TextInput::make('empatia')
                //     ->required(),
                // Forms\Components\TextInput::make('diccion')
                //     ->required(),

                // End of Fieldset Datos de la llamada
                ]),

        ]);
        
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Auditor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agente')
                    ->label('Agente')
                    ->formatStateUsing(fn (Calidad $calidad) => $calidad->agente->name),
                Tables\Columns\TextColumn::make('motivo_evaluacion')
                    ->label('Motivo de la evaluación')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venta_lineas')
                    ->numeric()
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
