<?php

namespace App\Filament\Resources\VentaLineaResource\Pages;

use App\Filament\Imports\VentaLineaImporter;
use App\Filament\Resources\VentaLineaResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
// use App\Filament\Resources\VentaLineaResource\Widgets\StatsOverview;
// use App\Models\VentaLinea;
use Filament\Resources\Pages\ListRecords;

class ListVentaLineas extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = VentaLineaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->color('primary')
                ->importer(VentaLineaImporter::class),
            Actions\CreateAction::make()
                ->label('Crear')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return VentaLineaResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'requiere_entrega' => Tab::make()->query(fn ($query) => $query->where('VentaLinea', 'Linea Nueva')->orWhere('VentaLinea', 'Portabilidad')),
            'portabilidad' => Tab::make()->query(fn ($query) => $query->where('VentaLinea', 'Portabilidad')),
            'linea_nueva' => Tab::make()->query(fn ($query) => $query->where('VentaLinea', 'Linea Nueva')),
            'migracion' => Tab::make()->label('Migración')->query(fn ($query) => $query->where('VentaLinea', 'Migracion')),
            // 'cancelled' => Tab::make()->query(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }
}
