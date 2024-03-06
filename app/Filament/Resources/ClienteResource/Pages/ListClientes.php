<?php

namespace App\Filament\Resources\ClienteResource\Pages;

// use App\Filament\Resources\ClienteResource;
// use Filament\Actions;
// use Filament\Resources\Pages\ListRecords;

use App\Filament\Imports\ClienteImporter;
use App\Filament\Exports\ClienteExporter;
use Filament\Actions\ExportAction;
// use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Resources\ClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
// use App\Filament\Resources\ClienteResource\Widgets\ClienteStatsWidget;
// use App\Filament\Resources\ClienteResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;


class ListClientes extends ListRecords
{
    use ExposesTableToWidgets;
    
    protected static string $resource = ClienteResource::class;

    protected function getHeaderWidgets(): array
    {
        return ClienteResource::getWidgets();
        
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Exportar')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                
                ->exporter(ClienteExporter::class),
                // ->formats([
                //     ExportFormat::CSV,
                //     ExportFormat::XLSX,
                // ]),
            Actions\ImportAction::make()
                ->label('Importar')
                ->color('primary')
                ->icon('heroicon-o-arrow-up-tray')
                ->importer(ClienteImporter::class),
            
            Actions\CreateAction::make()
                ->label('Crear')
                ->icon('heroicon-o-plus'),
        ];
    }
}
