<?php

namespace App\Filament\Resources\VentaLineaResource\Pages;

use App\Filament\Resources\VentaLineaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVentaLinea extends EditRecord
{
    protected static string $resource = VentaLineaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
