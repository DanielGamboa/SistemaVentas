<?php

namespace App\Filament\Resources\VentaLineaResource\Widgets;


use App\Filament\Resources\VentaLineaResource\Pages\ListVentaLineas;
use App\Models\VentaLinea;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


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

        // Current month
        $thisMonth = VentaLinea::where('created_at', '>=', now()->startOfMonth())
                        ->where('created_at', '<=', now())
                        ->count();
        return [
            //
            Stat::make('Ventas Mes', $thisMonth),
            Stat::make('Unique views', '100.1k'),
            Stat::make('Bounce rate', '1%'),
            Stat::make('Average time on page', '1:11'),
        ];
    }
}
