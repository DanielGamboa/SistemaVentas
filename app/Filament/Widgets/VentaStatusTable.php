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
use Illuminate\Support\collect;
use Illuminate\Support\Facades\DB;
use illuminate\Support\collection;
use illuminate\Support\Facades\collect as FacadesCollect;


class VentaStatusTable extends BaseWidget
{
    public function table(Table $table): Table
    {
        $query = \App\Models\VentaLinea::query();
                $estatusCounts = [];
                foreach (PlanesLibertyLineasEnum::toArray() as $estatus) {
                // $count = $query->where('estatus', $estatus)->count();
                // $estatusCounts[$estatus] = $count;
                $count = \App\Models\VentaLinea::query()->where('estatus', $estatus)->count();
                $estatusCounts[] = ['estatus' => $estatus, 'count' => $count];
                }

        return $table
            ->query(
                // Query the "linea_ventas" table and return the "estatus" column as "Estatus" count and group by "estatus"
                // \App\Models\VentaLinea::query()
                //     ->select('estatus', \Illuminate\Support\Facades\DB::raw('count(*) as Estatus'))
                //     ->groupBy('estatus')
                collect($estatusCounts)
            )
            ->columns([
                // ...
                TextColumn::make('estatus')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('count')
                    ->label('Count')
                    ->searchable()
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
