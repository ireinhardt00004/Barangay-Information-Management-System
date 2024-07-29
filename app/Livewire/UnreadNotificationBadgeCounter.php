<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class UnreadNotificationBadgeCounter extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function updateUnreadCount()
    {
        $this->unreadCount = Notification::where('receiver_id', Auth::id())
                                        ->where('is_read', false)
                                        ->count();
    }

    public function refresh()
    {
        // Method to be called to manually refresh the count
        $this->updateUnreadCount();
    }

    public function render()
    {
        return view('livewire.unread-notification-badge-counter');
    }

    protected $listeners = ['notificationUpdated' => 'refresh'];

    public function updated()
    {

        $this->refresh();
    }
}
