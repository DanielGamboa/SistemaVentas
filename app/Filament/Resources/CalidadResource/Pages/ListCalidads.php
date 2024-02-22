<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalidads extends ListRecords
{
    protected static string $resource = CalidadResource::class;

    protected static ?string $title = 'Calidad';

    

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Crear')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getCreateButtonLabel(): string
    {
        return 'Your Custom Create Text';
    }

    public function getCreateAnotherButtonLabel(): string
    {
        return 'Your Custom Create & Create Another Text';
    }

    public function getCancelButtonLabel(): string
    {
        return 'Your Custom Cancel Text';
    }
}
