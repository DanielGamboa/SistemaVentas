<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Illuminate\Support\Facades\Gate;

class ListCalidads extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = CalidadResource::class;

    protected static ?string $title = 'Calidad';

        protected function getHeaderWidgets(): array
    {
        return CalidadResource::getWidgets();
        
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Crear')
                ->icon('heroicon-o-plus')
                ->visible(fn() => Gate::allows('createCalidad', auth()->user())),
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
