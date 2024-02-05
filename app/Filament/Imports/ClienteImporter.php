<?php

namespace App\Filament\Imports;

use App\Models\Cliente;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ClienteImporter extends Importer
{
    protected static ?string $model = Cliente::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('provincias')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('cantones_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('distritos_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('user')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('tipo_documento')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('documento')
                ->requiredMapping()
                ->rules(['required', 'max:30']),
            ImportColumn::make('primer_nombre')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('segundo_nombre')
                ->rules(['max:255']),
            ImportColumn::make('primer_apellido')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('segundo_apellido')
                ->rules(['max:255']),
            ImportColumn::make('nombre_completo')
                ->rules(['max:255']),
            ImportColumn::make('documento_nombre_completo')
                ->rules(['max:255']),
            ImportColumn::make('direccion')
                ->requiredMapping()
                ->rules(['required', 'max:65535']),
            ImportColumn::make('documento_completo')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('documento_img')
                ->rules(['max:255']),
            ImportColumn::make('imagen_doc')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Cliente
    {
        // return Cliente::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Cliente();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your cliente import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
