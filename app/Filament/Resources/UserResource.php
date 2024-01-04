<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Forms\Set;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // Crea el menu lateral Settings, a todos los recursos que se le agregue esta linea van a entrar al mismo grupo
    protected static ?string $navigationGroup = 'Usuarios';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                    ->autocapitalize('words')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state)
                        {
                            // Set: is a built in function that takes two parameters 
                            // $name is the retrived value in this case the 'name' value
                            // Str::title() will make the first letter of every word capitalized and the rest lower case
                            $name = Str::title($state);
                            $set('name', $name);
                        })
                    ->required()
                    ->columnSpan(2),
                TextInput::make('cedula')->required(),
                TextInput::make('tlf')
                    ->numeric()
                    ->columnSpan(1),
                TextInput::make('email')->required()->email()->columnSpan(2),
                TextInput::make('usuario')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state)
                        {
                            // Set: is a built in function that takes two parameters 
                            // $removed is the retrived value in this case the 'usuario' value and we remove spaces
                            // Str::lower() will make the evry letter lower case
                            $removed = Str::remove(' ', $state);
                            $usuario = Str::lower($removed);
                            $set('usuario', $usuario);
                        })
                    ->columnSpan(2),
                DatePicker::make('fecha_ingreso')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    // ->minDate(now()->subYears(150))
                    ->maxDate(now())
                    ->suffixIcon('heroicon-m-calendar')
                    ->closeOnDateSelection(),
                // DatePicker::make('fecha_ingreso')->format('d/m/Y')->maxDate(now())->suffixIcon('heroicon-m-calendar'),
                Select::make('estado')->options([
                    'default' => '',
                    'activo' => 'Activo',
                    'inactivo' => 'Inactivo',
                    ])
                        ->default('default')
                        ->selectablePlaceholder(false)
                        ->required(),

                TextInput::make('role'),
                TextInput::make('password')->required()->password(),

            ])->columns(5);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tlf')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('usuario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('estado')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
