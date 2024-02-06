<?php

namespace App\Filament\Exports;

use App\Models\Distrito;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DistritoExporter extends Exporter
{
    protected static ?string $model = Distrito::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('provincias.id'),
            ExportColumn::make('cantones.id'),
            ExportColumn::make('DistritoNumber'),
            ExportColumn::make('distrito'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your distrito export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
