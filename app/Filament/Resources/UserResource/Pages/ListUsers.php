<?php

namespace App\Filament\Resources\UserResource\Pages;


use App\Filament\Imports\UserImporter;
use App\Filament\Exports\UserExporter;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
// use Filament\Actions\ImportAction;
// use Filament\Actions\CreateAction;
use Filament\Actions\Exports\Enums\DownloadFileFormat;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\Facades\Auth;






class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make('foo')
                ->color('primary')
                ->label('Exportar')
                ->icon('heroicon-o-arrow-down-tray')
                ->visible(fn() => Gate::allows('exportUsers', auth()->user()))
                ->exporter(UserExporter::class),
                // ->formats([
                //     ExportFormat::Csv, //=> 'CSV',
                //     ExportFormat::Xlsx, //=> 'XLSX',
                // ]),
        //         ->formData([
        //             DownloadFileFormat::Csv, //=> 'CSV',
        //             DownloadFileFormat::Xlsx, //=> 'XLSX',
        // ]),
            
            Actions\ImportAction::make()
                ->label('Importar')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->visible(fn() => Gate::allows('importUsers', auth()->user()))
                ->importer(UserImporter::class),
            Actions\CreateAction::make()
                ->label('Crear')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }

    public function getFormats(): array
{
    return [
        ExportFormat::Csv,
        ExportFormat::Xlsx,
        DownloadFileFormat::Csv,
        DownloadFileFormat::Xlsx,
    ];
}
}
