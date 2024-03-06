<?php

namespace App\Filament\Resources\CalidadResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Calidad;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Filament\Resources\CalidadResource\Pages\ListCalidads;





class CalidadStatsWidget extends BaseWidget
{
    use InteractsWithPageTable;
    
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListCalidads::class;
    }

    protected function getStats(): array
    {
        // Calculate the number of weekdays in the current month
        // The issue is that the DatePeriod class in PHP does
        // not include the end date in the period.
        // To include the end date in the period, you can add one day to the end date when creating the DatePeriod.
        // Here's how:
        $countToday = 5;
        $dailyGoal = 15;
        return [
            // Get Audits count for today
            // Query the database for the count of audits for today
            Stat::make('Hoy', $this->getPageTableQuery()->where('created_at', '>=', now()->startOfDay())->count()),
            // Query the database for the count of audits for this week
            Stat::make('Semana', $this->getPageTableQuery()->where('created_at', '>=', now()->startOfWeek())->count()),
            // Query the database for the count of audits for this month
            Stat::make('Mes', $this->getPageTableQuery()->where('created_at', '>=', now()->startOfMonth())->count()),
            // Query the database for the count of audits total
            Stat::make('Total', $this->getPageTableQuery()->count()),
        ];
    }
}
