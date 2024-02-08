
@livewire('notifications.database-notifications-trigger', ['form' => $form])
@livewire('DatabaseNotifications')
<div>
    <form wire:submit="create">
        {{ $this->form }}
        
        <button type="submit">
            Submit
        </button>
        <button type="button">
            Notifications ({{ $unreadNotificationsCount }} unread)
        </button>
    </form>
    
    <x-filament-actions::modals />
</div>