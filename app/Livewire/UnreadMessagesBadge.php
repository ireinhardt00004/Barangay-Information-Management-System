<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class UnreadMessagesBadge extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        // Initial load
        $this->updateUnreadCount();
    }

    public function updateUnreadCount()
    {
        // Count unread messages where the receiver is the authenticated user
        $this->unreadCount = Chat::where('receiver_id', Auth::id())
            ->where('seen', false)
            ->count();
    }

    public function render()
    {
        return view('livewire.unread-messages-badge');
    }
}
