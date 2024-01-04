<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;


// Enums
use App\Enums\TipoDocumentoEnum;
use App\Enums\ImagenesDocumentoEnum;
// Forms
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions\headerActions;
use Filament\Forms\Components\Actions\afterStateUpdated;
use Livewire\Component as Livewire;
use Filament\Forms\Set;
use Filament\Forms\Components\Tabs;
// Tables
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
// Models
use App\Models\Cliente;
use App\Models\Provincia;
use App\Models\Cantone;
use App\Models\Distrito;

// Filter methods
use Filament\Tables\Filters\SelectFilter;


class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

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
                                    ->autocapitalize('words')
                                    ->live(onBlur: true) // Will re-render and execute LiveWire componant after the user leaves the field
                                    ->afterStateUpdated(function (Set $set, $state)
                                        {
                                            // Set: is a built in function that takes two parameters 
                                            // $PrimerNombre is the retrived value in this case the 'primer_nombre' value
                                            // Str::title() will make the first letter of every word capitalized and the rest lower case
                                            $PrimerNombre = Str::title($state);
                                            $set('primer_nombre', $PrimerNombre);
                                        })
                                    ->columnSpan(2)
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('segundo_nombre')
                                    ->autocapitalize('words')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, $state)
                                        {
                                            // Set: is a built in function that takes two parameters 
                                            // $SegundoNombre is the retrived value in this case the 'segundo_nombre' value
                                            // Str::title() will make the first letter of every word capitalized and the rest lower case
                                            $SegundoNombre = Str::title($state);
                                            $set('segundo_nombre', $SegundoNombre);
                                        })
                                    ->columnSpan(2)
                                    ->columnSpan(2)
                                    ->maxLength(255),
                                TextInput::make('primer_apellido')
                                    ->autocapitalize('words')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, $state)
                                        {
                                            // Set: is a built in function that takes two parameters 
                                            // $PrimerApellido is the retrived value in this case the 'primer_apellido' value
                                            // Str::title() will make the first letter of every word capitalized and the rest lower case
                                            $PrimerApellido = Str::title($state);
                                            $set('primer_apellido', $PrimerApellido);
                                        })
                                    ->columnSpan(2)    
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('segundo_apellido')
                                    ->autocapitalize('words')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, $state)
                                        {
                                            // Set: is a built in function that takes two parameters 
                                            // $SegundoApellido is the retrived value in this case the 'segundo_apellido' value
                                            // Str::title() will make the first letter of every word capitalized and the rest lower case
                                            $SegundoApellido = Str::title($state);
                                            $set('segundo_apellido', $SegundoApellido);
                                        })
                                    ->columnSpan(2)
                                    ->maxLength(255), 
                            

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
                                        ->required()
                                    ])->columnSpan(6),
                                // Provincia, Canton, distrito de facturacion
                                Section::make()->schema([  
                                    // Provincia, Distrito, Canton de facturación
                                    Select::make('provincias_id')
                                        ->label('Provincia')
                                        ->options(Provincia::all()->pluck('provincia', 'id'))
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
                                            ->options(fn (Get $get): Collection => Cantone::query()
                                                ->where('id_provincias', $get('provincias_id'))
                                                // ->pluck('canton', 'id'))
                                                ->pluck('canton', 'CantonNumber'))
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set) {
                                                $set('distritos_id', null);
                                            })
                                            ->columnSpan(2)
                                            ->required(),
                                    Select::make('distritos_id')
                                            ->label('Distrito')
                                            ->options(fn (Get $get): Collection => Distrito::query()
                                                ->where('provincias_id','=',$get('provincias_id'))
                                                ->where('cantones_id', $get('cantones_id').'CantonNumber')
                                                ->pluck('distrito', 'id')
                                                )
                                            ->searchable()
                                            ->columnSpan(2)
                                            ->preload()
                                            ->live()
                                            ->required(),
                                ])->columnSpan(6),

                            ])
                                ->icon('heroicon-o-identification'),
                    // Pestaña para cargar las fotos de los documentos
                    Tabs\Tab::make('Imagenes')
                            ->schema([

                                Toggle::make('documento_completo')
                                    ->inline()
                                    ->live()
                                    ->onIcon('heroicon-o-document-check')
                                    ->offIcon('heroicon-o-document-minus')
                                    ->offColor('gray')
                                    ->columnSpan(3),
                                // Imagenes de cedulas y otros documentos
                                Section::make('Imagen Documento')->schema([
                                    // The name is the relationship from Cliente Model clientedocumento hasMany function
                                    Repeater::make('clientedocumento')
                                        ->relationship()
                                        ->schema([
                                        /*
                                        * tipo_documento needs to be changed for a Database field registered in the DB
                                        * or cast to an array either way it must be changed.
                                        */
                                        Select::make('tipo_documento')
                                            ->options(ImagenesDocumentoEnum::class),
                                            
                                        TextInput::make('documento_url'),
                                        ])
                                            ->grid(3)
                                            ->columnSpan(12)
                                            ->label('')
                                            ->addActionLabel('+ Documento'),

                                    ])->columns(12),
                                Section::make()->schema([

                                ])->label('Documentos'),
                            ])->columns(12)
                                ->icon('heroicon-o-camera'), 
                    ])->columns(12)->columnSpan(12)->contained(false),
                ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->formatStateUsing(function (string $state, Cliente $cliente)
                        {
                            $NombreCompletoCliente = $cliente->primer_nombre . ' ' . $cliente->segundo_nombre. ' ' . $cliente->primer_apellido. ' ' . $cliente->segundo_apellido;
                            $Nombre = Str::squish($NombreCompletoCliente);
                            return $Nombre;
                        })
                    ->searchable(),
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
                    ->sortable(),
                TextColumn::make('cantones_id')
                    ->formatStateUsing(function (Cliente $client) {
                        $canton = Cantone::where('id_provincias', $client->provincias_id)
                        ->where('CantonNumber', $client->cantones_id)
                        ->value('canton'); // Assuming 'canton' is the column for the canton name
                        return $canton;

                    })
                    ->label('Canton')
                    ->sortable(),
 
                TextColumn::make('distrito.distrito')
                    ->sortable(),
                TextColumn::make('direccion')
                    ->html()
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
