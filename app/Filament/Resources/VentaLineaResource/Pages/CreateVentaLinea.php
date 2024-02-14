<?php

namespace App\Filament\Resources\VentaLineaResource\Pages;

use App\Filament\Resources\VentaLineaResource;
use Filament\Resources\Pages\CreateRecord;


// Add actions, notification
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

// Add the following line
use App\Filament\Resources\CalidadResource;
use Illuminate\Support\Str;
use App\Models\Cliente;

class CreateVentaLinea extends CreateRecord
{
    protected static string $resource = VentaLineaResource::class;

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
        $venta = $this->record;
        // Get the client id of the current venta and get his fill name from Cliente model
        $cliente = $venta->clientes_id;
        // Get the full name of the client from the Cliente model
        $clienteId = Cliente::find($cliente);
        // Concatenate the full name of the client
        $NombreCompletoCliente = $clienteId->primer_nombre.' '.$clienteId->segundo_nombre.' '.$clienteId->primer_apellido.' '.$clienteId->segundo_apellido;
        // Remove extra spaces
        $Nombre = Str::squish($NombreCompletoCliente);

         // Get the full name of the client
        //  $NombreCompletoCliente = $cliente->primer_nombre.' '.$cliente->segundo_nombre.' '.$cliente->primer_apellido.' '.$cliente->segundo_apellido;
        //  // Remove extra spaces
        //  $Nombre = Str::squish($NombreCompletoCliente);
        
         return Notification::make()
             ->success()
             ->title('Cliente creado')
             ->body("El cliente $Nombre, solicito un plan {$venta->plan->value} para el télefono ($venta->tlf) ha sido registrado exitosamente. ")
             ->actions([
                 // Mark the notification as read
                 Action::make('Leido')
                     ->button()
                     ->markAsRead(true),
                 // Edit the current record
                 Action::make('edit')
                     ->label('Editar')
                     ->button()
                     ->url(VentaLineaResource::getUrl('edit', ['record' => $venta->id])),
                 // Create a new venta for the current client
                 Action::make('Calidad')
                     ->label('Auditar Venta')
                     ->button()
                     ->url(CalidadResource::getUrl('create')),
             ])
             ->sendToDatabase($recipient)
             ->send();
         
     }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->id]);
    }
}
