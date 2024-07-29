<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatMessages extends Component
{
    public $currentChatUserId;
    public $messages;
    public $newMessage;

    protected $listeners = [
        'messageSent' => 'loadMessages',
        'userSelected' => 'loadMessages',
        'confirmDeleteConversation' => 'confirmDeleteConversation'
    ];

    public function mount()
    {
        $this->currentChatUserId = session('currentChatUserId', null);
        $this->loadMessages($this->currentChatUserId);
    }

    public function loadMessages($userId = null)
    {
        $userId = $userId ?: $this->currentChatUserId;

        if ($userId) {
            $this->currentChatUserId = $userId;

            // Mark messages as seen for the current user
            Chat::where('receiver_id', Auth::id())
                ->where('sender_id', $userId)
                ->update(['seen' => true]);

            // Load messages
            $this->messages = Chat::where(function ($query) use ($userId) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
        } else {
            $this->messages = collect(); // Ensure messages is always a collection
        }
    }

    public function sendMessage()
    {
        if (!empty($this->newMessage) && $this->currentChatUserId) {
            $message = Chat::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $this->currentChatUserId,
                'message' => $this->newMessage,
                'seen' => false,
            ]);

            // Add the message to the messages collection
            $this->messages->push($message);

            // Clear the input field
            $this->reset('newMessage');

            // Notify the other user about the new message
            $this->dispatch('messageSent', $this->currentChatUserId);
        }
    }

    public function confirmDeleteConversation()
    {
        $this->dispatch('confirmDeleteConversation');
    }

    public function deleteConversation()
    {
        if (!$this->currentChatUserId) {
            return;
        }

        Chat::where(function ($query) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $this->currentChatUserId);
        })
        ->orWhere(function ($query) {
            $query->where('sender_id', $this->currentChatUserId)
                  ->where('receiver_id', Auth::id());
        })
        ->delete();

        $this->messages = collect();
        $this->dispatch('refreshChatMessages');
    }

    public function render()
    {
        return view('livewire.chat-messages', ['messages' => $this->messages]);
    }
}
