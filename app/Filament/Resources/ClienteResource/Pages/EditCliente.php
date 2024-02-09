<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Filament\Resources\VentaLineaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;


// Add actions, notification
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class EditCliente extends EditRecord
{
    protected static string $resource = ClienteResource::class;

    protected static ?string $title = 'Editar Cliente';

    protected ?string $heading = 'Editar Cliente';

     // Override the default notification on create
     protected function getSavedNotification(): ?Notification
     {
         $recipient = auth()->user();
         $cliente = $this->record;
         return Notification::make()
             ->success()
             ->title('Usuario editado')
             ->body("El usuario {$cliente->name} ha sido editado exitosamente.")
             ->actions([
                 Action::make('Leido')
                     ->button()
                     ->markAsRead(true),
                 Action::make('edit')
                     ->label('Editar')
                     ->button()
                     ->url(ClienteResource::getUrl('edit', ['record' => $cliente->id])),
                Action::make('Venta')
                     ->label('Nueva Venta')
                     ->button()
                     ->url(VentaLineaResource::getUrl('create')),

             ])
             ->sendToDatabase($recipient)
             ->send();
         
     }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}
