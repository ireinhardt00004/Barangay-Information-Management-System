<div>
    <!-- Comment Button -->
    <button id="commentButton-{{ $announcementId }}" style="background: none; border: none; cursor: pointer;" wire:click="toggleModal">
        <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 4H20C21.1046 4 22 4.89543 22 6V18C22 19.1046 21.1046 20 20 20H6L2 22V6C2 4.89543 2.89543 4 4 4Z"
                stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <!-- Comment Count -->
        <span>{{ $commentCount > 0 ? $commentCount : '' }}</span>
    </button>

    <!-- Modal -->
    <div id="commentsModal-{{ $announcementId }}" class="modal" style="{{ $showCommentForm ? 'display: block;' : 'display: none;' }}">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" wire:click="toggleModal">&times;</span>
                <h2>Comments</h2>
            </div>
            <div class="modal-body">
                <div class="comments-list">
                    @foreach($comments as $comment)
                        <div class="comment {{ $comment->user_id === auth()->id() ? 'my-comment' : '' }}">
                            <strong>{{ $comment->user->fname }} {{ $comment->user->lname }}:</strong>
                            <p>{{ $comment->comment }}</p>
                            <p>{{ $comment->created_at->diffForHumans() }}</p>
                            <!-- Comment Actions -->
                            @if($comment->user_id === auth()->id())
                                <div class="comment-actions">
                                    <button class="edit-comment" title="Edit Comment" wire:click="editComment({{ $comment->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-comment" title="Delete Comment"wire:confirm="Are you sure you want to delete this comment?" wire:click="deleteComment({{ $comment->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="comment-form">
                    <input type="text" wire:model="comment" placeholder="Type your comment...">
                    <button wire:click="toggleModal" style="background-color:gray;"><i class="fas fa-times"></i> Close</button>
                    <button wire:click="addComment"><i class="fas fa-envelope"></i> Send</button>
                    </div>
            </div>
        </div>
    </div>
<style>
    /* Style for the modal */
    .modal {
        display: none; /    
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5); 
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
    }

    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }

    .modal-body {
        margin-top: 10px;
    }

    /* Style for the comment form */
    .comment-form {
        margin-top: 10px;
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .comment-form input {
        width: 100%;
        height: 40px;
        border-radius: 4px;
        border: 1px solid #ccc;
        padding: 10px;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .comment-form button {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .comment-form button:hover {
        background-color: #0056b3;
    }

    /* Style for the comments list */
    .comments-list {
        max-height: 300px; 
        overflow-y: auto; 
        margin-top: 10px;
        border-top: 1px solid #ddd;
        padding-top: 10px;
        background-color: #f9f9f9;
    }

    .comment {
        margin-bottom: 10px;
        padding: 10px;
        background-color: white;
        border-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .comment strong {
        display: block;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .comment p {
        margin: 0;
        font-size: 14px;
    }

    .my-comment {
        background-color: #e0f7fa; 
        border-left: 5px solid #007bff; 
    }

    .comment-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        margin-left: 10px;
    }

    .edit-comment, .delete-comment {
        background-color: transparent;
        border: none;
        cursor: pointer;
        color: #007bff;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .edit-comment:hover, .delete-comment:hover {
        color: #0056b3;
    }
</style>
<script>
function closeModalButton() {
        const commentsModal = document.getElementById('commentsModal');
        commentsModal.style.display = 'none';
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const commentButton = document.getElementById('commentButton');
        const closeModalButton = document.getElementById('closeModalButton');
        const commentsModal = document.getElementById('commentsModal');
        const sendCommentButton = document.getElementById('sendCommentButton');

        // Show modal
        commentButton.addEventListener('click', function() {
            commentsModal.style.display = 'block';
        });

        // Hide modal
        closeModalButton.addEventListener('click', function() {
            commentsModal.style.display = 'none';
        });

        // Handle comment sending
        sendCommentButton.addEventListener('click', function(event) {
            event.stopPropagation();
            Livewire.dispatch('addComment');
        });

        // Handle click outside the modal to close it (optional)
        window.addEventListener('click', function(event) {
            if (event.target === commentsModal) {
                commentsModal.style.display = 'none';
            }
        });
    });
</script>
</div>
