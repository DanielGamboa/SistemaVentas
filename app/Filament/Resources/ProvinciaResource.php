<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProvinciaResource\Pages;
use App\Models\Provincia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Cache
use Illuminate\Support\Facades\Cache;
// Query Builder
use Illuminate\Database\Eloquent\Builder;

class ProvinciaResource extends Resource
{
    protected static ?string $navigationGroup = 'División Territorial';

    protected static ?int $navigationSort = 1;

    protected static ?string $model = Provincia::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function query(): Builder
{
    return Cache::rememberForever('provincias', function () {
        return Provincia::query();
    });
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('provincia')
                    ->required()
                    ->maxLength(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('provincia')
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
            // Provincia cannot be created or edited
            'index' => Pages\ListProvincias::route('/'),
            // 'create' => Pages\CreateProvincia::route('/create'),
            // 'edit' => Pages\EditProvincia::route('/{record}/edit'),
        ];
    }
}
