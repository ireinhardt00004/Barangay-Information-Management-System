<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationComponent extends Component
{
    public $messages = [];
    public $unreadCount = 0;

    protected $listeners = ['notificationUpdated' => 'refreshNotifications'];

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = Notification::where('receiver_id', Auth::id())
                                      ->orderBy('created_at', 'desc')
                                      ->get();
        $this->unreadCount = $this->messages->where('is_read', false)->count();
    }

    public function markAsRead($messageId)
    {
        $message = Notification::find($messageId);

        if ($message && $message->receiver_id === Auth::id()) {
            $message->update(['is_read' => true]);
            $this->loadMessages();
            $this->dispatch('notificationUpdated');
        }
    }

    public function deleteAllNotifications()
    {
        Notification::where('receiver_id', Auth::id())->delete();
        $this->loadMessages();
        $this->dispatch('notificationUpdated');
    }

    public function render()
    {
        return view('livewire.notification-component');
    }
}
