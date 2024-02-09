<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\VentaLineaResource;
use Illuminate\Support\Str;

// Add actions, notification
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class CreateCliente extends CreateRecord
{
    protected static string $resource = ClienteResource::class;

    protected static ?string $title = 'Crear Cliente';

    protected ?string $heading = 'Crear Cliente';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    // Override the default notification on create
    protected function getCreatedNotification(): ?Notification
    {
        // Notify the user that the record was created
        $recipient = auth()->user();
        // Pass current recrod to the notification
        $cliente = $this->record;
        // Get the full name of the client
        $NombreCompletoCliente = $cliente->primer_nombre.' '.$cliente->segundo_nombre.' '.$cliente->primer_apellido.' '.$cliente->segundo_apellido;
        // Remove extra spaces
        $Nombre = Str::squish($NombreCompletoCliente);

        return Notification::make()
            ->success()
            ->title('Cliente creado')
            ->body("El cliente $Nombre, con documento {$cliente->tipo_documento->value} {$cliente->documento} ha sido creado exitosamente.")
            ->actions([
                // Mark the notification as read
                Action::make('Leido')
                    ->button()
                    ->markAsRead(true),
                // Edit the current record
                Action::make('edit')
                    ->label('Editar')
                    ->button()
                    ->url(ClienteResource::getUrl('edit', ['record' => $cliente->id])),
                // Create a new venta for the current client
                Action::make('Venta')
                    ->label('Nueva Venta')
                    ->button()
                    ->url(VentaLineaResource::getUrl('create')),
            ])
            ->sendToDatabase($recipient)
            ->send();
        
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
