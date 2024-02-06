<?php

namespace App\Filament\Resources\CantoneResource\Pages;

use App\Filament\Exports\CantoneExporter;
use App\Filament\Resources\CantoneResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListCantones extends ListRecords
{
    protected static string $resource = CantoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
            Actions\ExportAction::make()
                ->color('primary')
                ->label('Exportar Cantones')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(CantoneExporter::class),
            // Button is disabled because we don't have an importer or be changed by the user
            // Actions\CreateAction::make(),
        ];
    }
}
