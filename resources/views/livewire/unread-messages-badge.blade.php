<div wire:poll.2s>
    <style>
        .nav-link-container {
            display: flex;
            align-items: center;
            position: relative;
        }

        .badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px; 
            font-size: 12px;
            font-weight: bold;
            margin-left: 8px; 
        }
    </style>

    @if ($unreadCount > 0)
        
            <!-- Badge for unread messages -->
            <span class="badge badge-danger">{{ $unreadCount }}</span>
    @endif
</div>

@livewireScripts
