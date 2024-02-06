<?php

namespace App\Filament\Exports;

use App\Models\Cliente;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ClienteExporter extends Exporter
{
    protected static ?string $model = Cliente::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('provincias.id'),
            ExportColumn::make('cantones_id'),
            ExportColumn::make('distritos_id'),
            ExportColumn::make('user.name'),
            ExportColumn::make('tipo_documento'),
            ExportColumn::make('documento'),
            ExportColumn::make('primer_nombre'),
            ExportColumn::make('segundo_nombre'),
            ExportColumn::make('primer_apellido'),
            ExportColumn::make('segundo_apellido'),
            ExportColumn::make('nombre_completo'),
            ExportColumn::make('documento_nombre_completo'),
            ExportColumn::make('direccion'),
            ExportColumn::make('documento_completo'),
            ExportColumn::make('documento_img'),
            ExportColumn::make('imagen_doc'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your cliente export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
