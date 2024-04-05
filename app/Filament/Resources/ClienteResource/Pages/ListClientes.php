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
use Illuminate\Support\Facades\Gate;



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
                ->visible(fn() => Gate::allows('exportClientes', auth()->user()))

                // ->visible(fn () => exportClient('exportClient'))
                // ->visible(fn () => Gate::allows('exportClient'))
                // ->visible(fn () => $this->authorizedTo('export-client'))
                
                ->exporter(ClienteExporter::class),
                // ->formats([
                //     ExportFormat::CSV,
                //     ExportFormat::XLSX,
                // ]),
            Actions\ImportAction::make()
                ->label('Importar')
                ->color('primary')
                ->icon('heroicon-o-arrow-up-tray')
                ->visible(fn() => Gate::allows('importClientes', auth()->user()))
                
                // ->visible(fn () => Gate::allows('exportClient'))
                ->importer(ClienteImporter::class),
            
            Actions\CreateAction::make()
                ->label('Crear')
                ->icon('heroicon-o-plus'),
        ];
    }
}
