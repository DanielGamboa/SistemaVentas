<?php

namespace App\Filament\Resources\CantoneResource\Pages;

use App\Filament\Resources\CantoneResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCantone extends CreateRecord
{
    protected static string $resource = CantoneResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
