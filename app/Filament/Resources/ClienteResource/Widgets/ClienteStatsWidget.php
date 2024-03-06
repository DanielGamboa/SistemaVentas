<?php

namespace App\Filament\Resources\ClienteResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Cliente;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Filament\Resources\ClienteResource\Pages\ListClientes;

class ClienteStatsWidget extends BaseWidget
{
    use InteractsWithPageTable;
    
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListClientes::class;
    }

    

    protected function getStats(): array
    {
        // Calculate the number of weekdays in the current month
        // The issue is that the DatePeriod class in PHP does not include the end date in the period. 
        // To include the end date in the period, you can add one day to the end date when creating the DatePeriod. 
        // Here's how:
        function getWeekdaysInCurrentMonth() {
            $date = new \DateTime(); // This will default to the current date
            $start = new \DateTime($date->format('Y-m-01')); // Start of the month
            $end = new \DateTime($date->format('Y-m-t')); // End of the month
            $end->modify('+1 day'); // Add one day to the end date
            $interval = new \DateInterval('P1D'); // 1 day interval
            $period = new \DatePeriod($start, $interval, $end); // Period from start to end
        
            $weekdays = 0;
            foreach ($period as $day) {
                // If the day is not a Saturday or Sunday, increment the counter
                if ($day->format('N') < 6) {
                    $weekdays++;
                }
            }
        
            return $weekdays;
        }
        // Total weekdays so far this month
        $weekDays = getWeekdaysInCurrentMonth();
        
        $dayOfWeek = now()->dayOfWeekIso; // Monday = 1, Sunday = 7
        $dayOfWeek = min($dayOfWeek, 5); // Limit to 5 (Friday)
        

        // $countToday = $this->getPageTableQuery()->where('created_at', '>=', now()->startOfDay())->count();
        $MonthlyGoal = 144;
        // MonthlyGoal devided by days of the week monday - friday for the current month
        $dailyGoal = ceil($MonthlyGoal / $weekDays);
        // Weekly goal
        $weeklyGoal = $dailyGoal * 5;
        // Adjusted goal for the current Month
        $adjustedMonthGoal = $dailyGoal * $weekDays;
        
        // Adjusted goal for the current week
        $adjustedWeekGoal = $dailyGoal * $dayOfWeek;

        // Todays Sales
        $countToday = $this->getPageTableQuery()->where('created_at', '>=', now()->startOfDay())->count();
        
        // Weekly goal
    
        // Sales Week
        $countWeek = $this->getPageTableQuery()->where('created_at', '>=', now()->startOfWeek())->count();
        // Sales Month
        $countMonth = $this->getPageTableQuery()->where('created_at', '>=', now()->startOfMonth())->count();
        
        
        // The ceil function is used to round up the number to the nearest whole number vs. round which rounds to the nearest whole number
        $halfTargetToday = ceil($dailyGoal / 2);
        // $halfTargetWeek = ceil($weeklyGoal / 2);
        return [
            // New clients Today
            Stat::make('Hoy', $countToday . ' / ' . $dailyGoal)
                ->color(
                    $countToday >= $dailyGoal ? 'success' :
                    (now()->hour >= 12 && $countToday < $halfTargetToday ? 'danger' :
                    (now()->hour < 12 && $countToday >= $halfTargetToday ? 'success' :
                    (now()->hour < 12 && $countToday < $halfTargetToday ? 'gray' :
                    (now()->hour >= 12 && $countToday >= $halfTargetToday && $countToday < $dailyGoal ? 'warning' : 'default')))))
                ->description(
                    $countToday >= $dailyGoal ? 'Sigue así' :
                    (now()->hour >= 12 && $countToday < $halfTargetToday ? 'Vaya por la meta' :
                    (now()->hour < 12 && $countToday >= $halfTargetToday ? 'Sigue así' :
                    (now()->hour < 12 && $countToday < $halfTargetToday ? 'Vaya por la meta' :
                    (now()->hour >= 12 && $countToday >= $halfTargetToday && $countToday < $dailyGoal ? 'Vaya por la meta' : 'Sigue así')))))

                ->descriptionIcon(
                        $countToday >= $dailyGoal ? 'heroicon-m-arrow-trending-up' :
                        (now()->hour >= 12 && $countToday < $halfTargetToday ? 'heroicon-m-arrow-trending-down' :
                        (now()->hour < 12 && $countToday >= $halfTargetToday ? 'heroicon-m-arrow-trending-up' :
                        (now()->hour < 12 && $countToday < $halfTargetToday ? 'heroicon-m-arrow-trending-down' :
                        (now()->hour >= 12 && $countToday >= $halfTargetToday && $countToday < $dailyGoal ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up'))))),

            // New clientes this week
            Stat::make('Semana', $countWeek . ' / ' . $weeklyGoal)
                ->color(
                    $countWeek >= $weeklyGoal ? 'success' :
                    ($countWeek < $adjustedWeekGoal ? 'danger' :
                    ($countWeek >= $adjustedWeekGoal ? 'success' :
                    ($countWeek < $adjustedWeekGoal ? 'gray' :
                    ($countWeek >= $adjustedWeekGoal && $countWeek < $weeklyGoal ? 'warning' : 'default')))))
                ->description(
                    $countWeek >= $weeklyGoal ? 'Sigue así' :
                    ($countWeek < $adjustedWeekGoal ? 'Vaya por la meta' :
                    ($countWeek >= $adjustedWeekGoal ? 'Sigue así' :
                    ($countWeek < $adjustedWeekGoal ? 'Vaya por la meta' :
                    ($countWeek >= $adjustedWeekGoal && $countWeek < $weeklyGoal ? 'Vaya por la meta' : 'Sigue así')))))
                ->descriptionIcon(
                    $countWeek >= $weeklyGoal ? 'heroicon-m-arrow-trending-up' :
                    ($countWeek < $adjustedWeekGoal ? 'heroicon-m-arrow-trending-down' :
                    ($countWeek >= $adjustedWeekGoal ? 'heroicon-m-arrow-trending-up' :
                    ($countWeek < $adjustedWeekGoal ? 'heroicon-m-arrow-trending-down' :
                    ($countWeek >= $adjustedWeekGoal && $countWeek < $weeklyGoal ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up'))))),

            // New clients this month 
            Stat::make('Mes', $countMonth . ' / ' . $MonthlyGoal)
                ->color(
                    $countMonth >= $weeklyGoal ? 'success' :
                    ($countMonth < $adjustedMonthGoal ? 'danger' :
                    ($countMonth >= $adjustedMonthGoal ? 'success' :
                    ($countMonth < $adjustedMonthGoal ? 'gray' :
                    ($countMonth >= $adjustedMonthGoal && $countMonth < $weeklyGoal ? 'warning' : 'default')))))
                ->description(
                    $countMonth >= $weeklyGoal ? 'Sigue así' :
                    ($countMonth < $adjustedMonthGoal ? 'Vaya por la meta' :
                    ($countMonth >= $adjustedMonthGoal ? 'Sigue así' :
                    ($countMonth < $adjustedMonthGoal ? 'Vaya por la meta' :
                    ($countMonth >= $adjustedMonthGoal && $countMonth < $weeklyGoal ? 'Vaya por la meta' : 'Sigue así')))))
                ->descriptionIcon(
                    $countMonth >= $weeklyGoal ? 'heroicon-m-arrow-trending-up' :
                    ($countMonth < $adjustedMonthGoal ? 'heroicon-m-arrow-trending-down' :
                    ($countMonth >= $adjustedMonthGoal ? 'heroicon-m-arrow-trending-up' :
                    ($countMonth < $adjustedMonthGoal ? 'heroicon-m-arrow-trending-down' :
                    ($countMonth >= $adjustedMonthGoal && $countMonth < $weeklyGoal ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up'))))),
            // Total clients
            Stat::make('Clientes', $this->getPageTableQuery()->count()),
        ];
    }
}
