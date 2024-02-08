<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use Filament\Resources\Pages\CreateRecord;

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
        $recipient = auth()->user();
        $user = $this->record;
        return Notification::make()
            ->success()
            ->title('Cliente creado')
            ->body("El cliente {$user->name} ha sido creado exitosamente.")
            ->actions([
                Action::make('Leido')
                    ->button()
                    ->markAsRead(true),
                Action::make('edit')
                    ->label('Editar')
                    ->button()
                    ->url(ClienteResource::getUrl('edit', ['record' => $user->id])),
            ])
            ->sendToDatabase($recipient)
            ->send();
        
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
