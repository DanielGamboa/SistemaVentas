<?php

namespace App\Filament\Resources\VentaLineaResource\Pages;

use App\Filament\Resources\VentaLineaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVentaLinea extends CreateRecord
{
    protected static string $resource = VentaLineaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
