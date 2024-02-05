<?php

namespace App\Filament\Resources\VentaLineaResource\Pages;

use App\Filament\Imports\VentaLineaImporter;
use App\Filament\Resources\VentaLineaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVentaLineas extends ListRecords
{
    protected static string $resource = VentaLineaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->color('primary')
                ->importer(VentaLineaImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
