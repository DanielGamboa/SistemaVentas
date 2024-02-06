<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalidads extends ListRecords
{
    protected static string $resource = CalidadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Crear')
                ->icon('heroicon-o-plus'),
        ];
    }
}
