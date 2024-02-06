<?php

namespace App\Filament\Resources\ProvinciaResource\Pages;

use App\Filament\Resources\ProvinciaResource;
use App\Filament\Exports\ProvinciaExporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProvincias extends ListRecords
{
    protected static string $resource = ProvinciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->exporter(ProvinciaExporter::class)
                ->label('Exportar Provincias')
                ->color('primary')
                ->icon('heroicon-o-arrow-down-tray'),
            // Provincias can't be created from the list page.  They can only be populated from seeder.
            // Actions\CreateAction::make(),
        ];
    }
}
