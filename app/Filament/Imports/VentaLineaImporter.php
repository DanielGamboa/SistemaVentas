<?php

namespace App\Filament\Imports;

use App\Models\VentaLinea;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class VentaLineaImporter extends Importer
{
    protected static ?string $model = VentaLinea::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('provincias')
                ->relationship(),
            ImportColumn::make('cantones_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('distritos_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('user')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('clientes_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('VentaLinea')
                ->requiredMapping()
                ->rules(['required', 'max:15']),
            ImportColumn::make('plan')
                ->requiredMapping()
                ->rules(['required', 'max:17']),
            ImportColumn::make('precio')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('Estatus')
                ->requiredMapping()
                ->rules(['required', 'max:50']),
            ImportColumn::make('tlf')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('tlf_venta_distinto')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('tlf_marcado')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('entrega_distinta')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('direccion_entrega')
                ->rules(['max:65535']),
        ];
    }

    public function resolveRecord(): ?VentaLinea
    {
        // return VentaLinea::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new VentaLinea();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your venta linea import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
