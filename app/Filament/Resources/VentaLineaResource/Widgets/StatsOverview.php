<?php

namespace App\Filament\Resources\VentaLineaResource\Widgets;


use App\Filament\Resources\VentaLineaResource\Pages\ListVentaLineas;
use App\Models\VentaLinea;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;


// use App\Filament\Resources\VentaLineaResource;


// use Filament\Pages\Concerns\ExposesTableToWidgets;


class StatsOverview extends BaseWidget
{

    use InteractsWithPageTable;
    
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListVentaLineas::class;
    }
    
    protected function getStats(): array
    {

        // Last year
        $ventaData = Trend::model(VentaLinea::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        // Current month
        $thisMonth = VentaLinea::where('created_at', '>=', now()->startOfMonth())
                        ->where('created_at', '<=', now())
                        ->count();
        return [
            //
            Stat::make('Ventas Mes', $thisMonth),
            Stat::make('VentaLinea', $this->getPageTableQuery()->count())
                ->chart(
                    $ventaData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                )
                ->color('primary'),
            Stat::make('Requiere Entrega', $this->getPageTableQuery()->whereIn('VentaLinea', ['Linea Nueva', 'Portabilidad'])->count()),
            Stat::make('Migración', $this->getPageTableQuery()->whereIn('VentaLinea', ['Migracion'])->count()),
        ];
    }
}
