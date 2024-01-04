<?php

namespace App\Filament\Resources\CantoneResource\Pages;

use App\Filament\Resources\CantoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCantones extends ListRecords
{
    protected static string $resource = CantoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
