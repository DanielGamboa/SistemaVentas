<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Cliente;


class StatsOverview extends BaseWidget
{
    // protected static ?string $pollingInterval = '10s';
    protected static ?string $pollingInterval = null;
    
    protected function getStats(): array

    {
    // Current month
    // $thisMonth = Cliente::whereMonth('created_at', '=', now()->month)->count(); --> Presumes full current month including future dates for this month
    $thisMonth = Cliente::where('created_at', '>=', now()->startOfMonth())
                        ->where('created_at', '<=', now())
                        ->count();
    
    // Previus month
    $previousMonthCount = Cliente::whereMonth('created_at', '=', now()->subMonth()->month)->count();
    // dd($previousMonthCount); -> 67 clients
    $currentCount = Cliente::count();
    // dd($currentCount); // 815 clients
    // $percentageChange = (($currentCount - $previousMonthCount) / $previousMonthCount) * 100;
    // Growth

    $percentageChange = function($currentCount, $thisMonth) {
        $difference = $currentCount - $thisMonth;
        if ($difference == 0){
        return 0;
    }else {
        return (($thisMonth / $difference) * 100);
    }};

    $percentageChangeValue = $percentageChange($currentCount, $thisMonth);  

    // Evaluate $percentageChange if positive, it grew if negative it decresed
    $descriptionResultIcon = function () use ($percentageChangeValue) {
        if ($percentageChangeValue > 0) {
            return 'heroicon-m-arrow-trending-up'; // Replace with the actual icon for growth
        } elseif ($percentageChangeValue < 0) {
            return 'heroicon-m-arrow-trending-down'; // Replace with the actual icon for decrease
        } else {
            return 'heroicon-m-arrow-long-right'; // Replace with the actual icon for no change
        }
    };
    // Calculate % change
    

    $counts = collect(range(5, 0))->map(function ($monthsAgo) {
        $month = now()->subMonths($monthsAgo);
        return Cliente::whereYear('created_at', $month->year)
                      ->whereMonth('created_at', $month->month)
                      ->count();
    })->toArray();
    
        return [
        // HeroIcon list for Stat heroicon-m-arrow-trending-up, heroicon-m-arrow-trending-down, heroicon-m-arrow-long-right
        // Count total customers from Cliente Model
        Stat::make('Total Clientes', $currentCount)
            // Description calculate percent change with regard to previos month
            ->description('Cambio respecto al mes anterior: ' . number_format($percentageChangeValue, 2) . '%')
            ->descriptionIcon($descriptionResultIcon())
            ->chart($counts),
        ];


    }

    protected function getData(): array
{
    return [
        'datasets' => [
            [
                'label' => 'Blog posts created',
                'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                'backgroundColor' => '#36A2EB',
                'borderColor' => '#9BD0F5',
            ],
        ],
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    ];
}


}
