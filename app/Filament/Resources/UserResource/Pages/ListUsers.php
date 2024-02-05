<?php

namespace App\Filament\Resources\UserResource\Pages;


use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;






class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->color('primary')
                ->importer(UserImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
