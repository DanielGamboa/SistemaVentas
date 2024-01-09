<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CantoneResource\Pages;
use App\Models\Cantone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CantoneResource extends Resource
{
    protected static ?string $navigationGroup = 'División Territorial';

    protected static ?int $navigationSort = 2;

    protected static ?string $model = Cantone::class;

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
                Tables\Columns\TextColumn::make('provincias.provincia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CantonNumber')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('canton')
                    ->searchable(),
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
            'index' => Pages\ListCantones::route('/'),
            'create' => Pages\CreateCantone::route('/create'),
            'edit' => Pages\EditCantone::route('/{record}/edit'),
        ];
    }
}
