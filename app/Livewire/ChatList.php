<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatList extends Component
{
    public $search = '';
    public $currentChatUserId;

    protected $listeners = [
        'refreshChatMessages' => '$refresh',
        'userSelected' => 'loadMessages'
    ];

    public function mount()
    {
        $this->currentChatUserId = session('currentChatUserId', null);
    }

    public function selectUser($userId)
    {
        $this->currentChatUserId = $userId;
        session(['currentChatUserId' => $userId]);

        $chatExists = Chat::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $userId);
        })
        ->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', Auth::id());
        })
        ->exists();

        if (!$chatExists) {
            Chat::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $userId,
                'message' => '',
                'seen' => false
            ]);
        }

        $this->dispatch('userSelected', $userId);
    }

    public function searchUsers()
    {
        return User::where('lname', 'like', '%'.$this->search.'%')
                   ->orWhere('fname', 'like', '%'.$this->search.'%')
                   ->orWhere('middlename', 'like', '%'.$this->search.'%')
                   ->get();
    }

    public function chatsList()
    {
        $authId = Auth::id();
    
        // Retrieve users who have either sent messages to or received messages from the authenticated user
        $sentMessageUserIds = Chat::where('sender_id', $authId)
                                  ->pluck('receiver_id')
                                  ->toArray();
    
        $receivedMessageUserIds = Chat::where('receiver_id', $authId)
                                      ->pluck('sender_id')
                                      ->toArray();
    
        // Combine and filter unique user IDs
        $userIdsWithMessages = array_unique(array_merge($sentMessageUserIds, $receivedMessageUserIds));
    
        // Include the authenticated user's ID in the list
        $userIdsWithMessages[] = $authId;
    
        // Retrieve users with their infos
        $users = User::with('userinfos')
                     ->whereIn('id', $userIdsWithMessages)
                     ->orderBy('lname')
                     ->get();
    
        // Debugging: Log the retrieved users
        \Log::info('Users retrieved:', $users->toArray());
    
        foreach ($users as $user) {
            $recentMessage = Chat::where(function ($query) use ($authId, $user) {
                $query->where(function ($subQuery) use ($authId, $user) {
                    $subQuery->where('sender_id', $authId)
                             ->where('receiver_id', $user->id);
                })
                ->orWhere(function ($subQuery) use ($authId, $user) {
                    $subQuery->where('sender_id', $user->id)
                             ->where('receiver_id', $authId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();
    
            $user->recentMessage = $recentMessage;
        }
    
        return $users;
    }
    
    public function render()
    {
        $searchResults = $this->searchUsers();
        $chatUsers = $this->chatsList();

        return view('livewire.chat-list', [
            'searchResults' => $searchResults,
            'chatUsers' => $chatUsers
        ]);
    }
}
