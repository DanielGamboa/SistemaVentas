<?php

namespace App\Filament\Resources\DistritoResource\Pages;

use App\Filament\Resources\DistritoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDistrito extends CreateRecord
{
    protected static string $resource = DistritoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
