<?php

namespace App\Filament\Resources\UserResource\Pages;


use App\Filament\Imports\UserImporter;
use App\Filament\Exports\UserExporter;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;






class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->color('primary')
                ->label('Exportar')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(UserExporter::class),
            Actions\ImportAction::make()
                ->label('Importar')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->importer(UserImporter::class),
            Actions\CreateAction::make()
                ->label('Crear')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }
}
