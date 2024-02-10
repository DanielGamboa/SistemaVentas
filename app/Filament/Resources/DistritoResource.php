<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistritoResource\Pages;
use App\Models\Distrito;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Cache
use Illuminate\Support\Facades\Cache;
// Query Builder
use Illuminate\Database\Eloquent\Builder;

class DistritoResource extends Resource
{
    protected static ?string $navigationGroup = 'División Territorial';

    protected static ?int $navigationSort = 3;

    protected static ?string $model = Distrito::class;

    public static function query(): Builder
    {
        return Cache::rememberForever('distritos', function () {
            return Distrito::query();
        });
    }    

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('provincias_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cantones_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('DistritoNumber')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('distrito')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('provincias.provincia')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantones.canton')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('DistritoNumber')
                    ->label('Numero Distrito')
                    ->sortable(),
                Tables\Columns\TextColumn::make('distrito')
                    ->searchable(),
            ])
            ->filters([
                //
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
            // Distritos cant be created or edited only exported
            'index' => Pages\ListDistritos::route('/'),
            // 'create' => Pages\CreateDistrito::route('/create'),
            // 'edit' => Pages\EditDistrito::route('/{record}/edit'),
        ];
    }
}
