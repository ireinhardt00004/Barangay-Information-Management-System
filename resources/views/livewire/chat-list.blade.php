<div>
    <input wire:model.live="search" type="search" class="form-control mb-2" placeholder="Search name">

    @if($search)
        <div id="search-results" class="list-group" style="max-height: 80vh; overflow-y: auto;" wire:poll.1s>
            @if($searchResults->isEmpty())
                <div class="list-group-item">
                    <strong>No users found</strong>
                </div>
            @else
                @foreach ($searchResults as $user)
                    <a href="#" wire:click.prevent="selectUser({{ $user->id }})" class="list-group-item list-group-item-action {{ $currentChatUserId == $user->id ? 'font-weight-bold' : '' }}">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('profile_pic/' . ($user->userinfos->profile_pic ?? 'default-avatar.png')) }}" class="rounded-circle" width="40" height="40" alt="Profile Picture">
                            <div class="ms-2">
                                <strong>{{ $user->fname }} {{ $user->middlename }} {{ $user->lname }}</strong>
                                <div class="text-muted">{{ $user->roles }}</div>
                                @if ($user->recentMessage)
                                    <div class="text-muted">
                                        {{ $user->recentMessage->message }}
                                        @if (!$user->recentMessage->seen && $user->recentMessage->receiver_id == Auth::id())
                                            <strong class="text-primary"> (New)</strong>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    @else
        <hr>
        <h5>Chat Users</h5>
        <div id="chat-users" class="list-group" style="max-height: 80vh; overflow-y: auto;">
            @if($chatUsers->isEmpty())
                <div class="list-group-item">
                    <strong>No chats available</strong>
                </div>
            @else
                @foreach ($chatUsers as $user)
                    <a href="#" wire:click.prevent="selectUser({{ $user->id }})" class="list-group-item list-group-item-action {{ $currentChatUserId == $user->id ? 'font-weight-bold' : '' }}">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('profile_pic/' . ($user->userinfos->profile_pic ?? 'default-avatar.png')) }}" class="rounded-circle" width="40" height="40" alt="Profile Picture">
                            <div class="ms-2">
                                <strong>{{ $user->fname }} {{ $user->middlename }} {{ $user->lname }}</strong>
                                <div class="text-muted">{{ $user->roles }}</div>
                                @if ($user->recentMessage)
                                    <div class="text-muted">
                                        {{ $user->recentMessage->message }}
                                        @if (!$user->recentMessage->seen && $user->recentMessage->receiver_id == Auth::id())
                                            <strong class="text-primary"> (New)</strong>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    @endif
</div>
