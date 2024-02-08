<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;


// Add actions, notification
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;



class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Crear Usuario';

    protected ?string $heading = 'Crear Usuario';

    // Override the default notification on create
    protected function getCreatedNotification(): ?Notification
{
    $recipient = auth()->user();
    $user = $this->record;
    return Notification::make()
        ->success()
        ->title('Usuario creado')
        ->body("El Usuario {$user->name} ha sido creado exitosamente.")
        ->actions([
            Action::make('Leido')
                ->button()
                ->markAsRead(true),
            Action::make('edit')
                ->label('Editar')
                ->button()
                ->url(UserResource::getUrl('edit', ['record' => $user->id])),
        ])
        ->sendToDatabase($recipient)
        ->send();
    
}


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
