<div>
    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .chat-messages {
            flex: 1;
            max-height: 70vh;
            overflow-y: auto;
            padding: 10px;
            background-color: #f0f2f5;
        }

        .chat-bubble {
            max-width: 80%;
            padding: 12px;
            border-radius: 20px;
            position: relative;
            display: inline-block;
            font-size: 16px;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .chat-bubble.bg-primary {
            background-color: #007bff;
            color: white;
            border-bottom-right-radius: 0;
        }

        .chat-bubble.bg-light {
            background-color: #ffffff;
            color: black;
            border-bottom-left-radius: 0;
        }

        .chat-meta {
            font-size: 12px;
            position: absolute;
            bottom: -20px;
            right: 10px;
            color: #6c757d;
        }

        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .text-muted {
            color: #6c757d;
        }

        .text-success {
            color: #28a745;
        }

        .input-container {
            margin-top: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            position: relative;
        }

        .user-info img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .user-info h3 {
            margin: 0;
        }

        .delete-conversation {
            cursor: pointer;
            color: red;
            font-size: 18px;
            margin-left: auto;
            margin-right: 10px;
        }
    </style>

    <div class="chat-container">
        @if($messages->count() > 0)
            @php
                // Load receiver user model
                $receiverId = $messages->first()->receiver_id;
                $receiver = \App\Models\User::find($receiverId);
            @endphp
            @if($receiver)
                <div class="user-info">
                    <img src="{{ asset('profile_pic/' . ($receiver->userinfos->profile_pic ?? 'default-avatar.png')) }}" 
                         alt="{{ $receiver->fname }} {{ $receiver->middlename }} {{ $receiver->lname }}">
                    <h3>{{ $receiver->fname }} {{ $receiver->middlename }} {{ $receiver->lname }}</h3>
                    <span class="delete-conversation" 
                          onclick="confirmConversationDelete({{ $receiver->id }});"
                          title="Delete Conversation">&#128465;</span>
                </div>
            @endif

            <div id="chat-messages" class="chat-messages">
                @foreach ($messages as $message)
                    <div class="d-flex mb-2 {{ $message->sender_id === Auth::id() ? 'justify-content-end' : '' }}">
                        <div class="chat-bubble {{ $message->sender_id === Auth::id() ? 'bg-primary text-white' : 'bg-light' }}">
                            <h5>{{ $message->message }}</h5>
                            {{ $message->created_at->diffForHumans() }}
                            <div class="chat-meta">
                                @if ($message->seen && $message->receiver_id == Auth::id())
                                    <span class="text-success">Seen</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No messages</p>
        @endif

        <div class="input-container">
            <input 
                type="text" 
                wire:model="newMessage" 
                class="form-control" 
                placeholder="Type a message" 
                wire:keydown.enter="sendMessage"
            >
        </div>
    </div>
</div>
