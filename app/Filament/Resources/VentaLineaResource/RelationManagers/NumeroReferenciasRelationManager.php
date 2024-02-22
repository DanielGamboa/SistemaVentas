<?php

namespace App\Filament\Resources\VentaLineaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NumeroReferenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'numeroReferencias';

    // Disable default behavior in order to enable, create and edit.
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('id')
                //     ->required()
                //     ->maxLength(255),
                Forms\Components\TextInput::make('numeroreferencia')
                    ->numeric()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contacto')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('numeroreferencia'),
                Tables\Columns\TextColumn::make('contacto'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Agregar')->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar')->icon('heroicon-o-pencil'),
                Tables\Actions\DeleteAction::make()->label('Eliminar')->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
