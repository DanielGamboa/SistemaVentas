<?php

namespace App\Filament\Resources\DistritoResource\Pages;

use App\Filament\Exports\DistritoExporter;
use App\Filament\Resources\DistritoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDistritos extends ListRecords
{
    protected static string $resource = DistritoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->color('primary')
                ->label('Exportar Usuarios')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(DistritoExporter::class),
            // Distritos cant be created or imported by the user
            // Actions\CreateAction::make(),
        ];
    }
}
