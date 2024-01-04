<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalidad extends EditRecord
{
    protected static string $resource = CalidadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
