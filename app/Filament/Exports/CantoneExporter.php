<?php

namespace App\Filament\Exports;

use App\Models\Cantone;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use App\Filament\Resources\CantoneResource;
use Filament\Actions\Exports\Models\Export;

class CantoneExporter extends Exporter
{
    protected static ?string $model = Cantone::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('id_provincias'),
            ExportColumn::make('CantonNumber'),
            ExportColumn::make('canton'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your cantone export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
