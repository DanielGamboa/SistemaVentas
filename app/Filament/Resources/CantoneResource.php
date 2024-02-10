<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CantoneResource\Pages;
use App\Models\Cantone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Cache
use Illuminate\Support\Facades\Cache;
// Query Builder
use Illuminate\Database\Eloquent\Builder;

class CantoneResource extends Resource
{
    protected static ?string $navigationGroup = 'División Territorial';

    protected static ?int $navigationSort = 2;

    protected static ?string $model = Cantone::class;

    public static function query(): Builder
{
    return Cache::rememberForever('cantones', function () {
        return Cantone::query();
    });
}

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_provincias')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('CantonNumber')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('canton')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('id_provincias')
                //     ->label('Provincia Id')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('provincias.provincia')
                    ->label('Provincia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CantonNumber')
                    ->label('Cantón Id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('canton')
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
            //  Contones records canot be created or edited
            'index' => Pages\ListCantones::route('/'),
            // 'create' => Pages\CreateCantone::route('/create'),
            // 'edit' => Pages\EditCantone::route('/{record}/edit'),
        ];
    }
}
