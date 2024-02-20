<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use App\Enums\PlanesLibertyLineasEnum;
// use Filament\Tables\Columns\NumberColumn;
// use App\Filament\Widgets\NumberColumn as WidgetsNumberColumn;
use Filament\Tables\Columns\NumberColumn;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\Summarizers\Sum;






class VentaStatusTable extends BaseWidget
{
    
    public function table(Table $table): Table
    {
        return $table
        ->poll('10s')
        ->heading('Ventas por estatus')
        ->hiddenFilterIndicators()
        ->paginated(false)
        // ->rowClass(fn ($record) => match ($record->estatus) {
        //     'pending' => 'bg-yellow-100',
        //     'approved' => 'bg-green-100',
        //     'rejected' => 'bg-red-100',
        //     default => '',
        // })
        ->recordClasses(fn (\App\Models\EstatusCount $record) => match ($record->status) {
            'draft' => 'opacity-50',
            'Aplica' => 'border-s-2 border-orange-600 dark:border-orange-300',
            'Aplica reconsideración' => 'border-s-2 border-green-600 dark:border-green-300',
            default => 'py-1',
        })
        ->query(
            // Query EstausCount model and return the "estatus" column as "Estatus" count and group by "estatus"
            \App\Models\EstatusCount::query()->select('ID', 'estatus', 'count_week', 'count_month', 'count')
        )
            ->columns([
                // ...
                TextColumn::make('estatus')
                    ->label('Estatus')
                    ->summarize(Sum::make()    
                        ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->sortable(),
                TextColumn::make('count_week')
                    ->label('Semana')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->summarize(Sum::make()    
                        ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->sortable(),
                TextColumn::make('count_month')
                    ->label('Mes')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->summarize(Sum::make()    
                        ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    ))
                    ->sortable(),
                TextColumn::make('count')
                    ->label('Histórico')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->summarize(Sum::make()
                        ->numeric(
                            decimalPlaces: 0,
                            decimalSeparator: '.',
                            thousandsSeparator: ',',
                        ))
                    ->sortable(),

                // TextColumn::make('Estatus')
                //     ->label('Status')
                //     ->searchable()
                //     ->sortable()
                    // ->format(function ($value) {
                    //     return match ($value) {
                    //         'pending' => 'Pendiente',
                    //         'approved' => 'Aprobado',
                    //         'rejected' => 'Rechazado',
                    //         default => $value,
                    //     };
                    // }),
                    ]);
    }
}
