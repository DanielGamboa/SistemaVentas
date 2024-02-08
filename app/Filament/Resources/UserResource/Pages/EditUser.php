<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

// Add actions, notification
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Editar Usuario';

    protected ?string $heading = 'Editar Usuario';

     // Override the default notification on create
     protected function getSavedNotification(): ?Notification
     {
         $recipient = auth()->user();
         $user = $this->record;
         return Notification::make()
             ->success()
             ->title('Usuario editado')
             ->body("El usuario {$user->name} ha sido editado exitosamente.")
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
