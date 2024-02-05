<?php

namespace App\Filament\Resources\ClienteResource\Pages;

// use App\Filament\Resources\ClienteResource;
// use Filament\Actions;
// use Filament\Resources\Pages\ListRecords;

use App\Filament\Imports\ClienteImporter;
use App\Filament\Resources\ClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientes extends ListRecords
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->color('primary')
                ->importer(ClienteImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
