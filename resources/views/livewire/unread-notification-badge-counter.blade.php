<div>
  
    @if($unreadCount > 0)
        <span class="notification-badge">{{ $unreadCount }}</span>
    @endif
    <style>
        /* Style for the notification badge */
        .notification-badge {
            position: absolute;
            top: 10px; 
            right: 20px; 
            background-color: red; 
            color: white; 
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 12px; 
            font-weight: bold;
            min-width: 20px; 
            text-align: center; 
        }
</style>
</div>
