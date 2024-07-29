<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NewsReact;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class CommentSection extends Component
{
    public $announcementId;
    public $comment;
    public $comments = [];
    public $showCommentForm = false;
    public $commentCount = 0;
    public $editCommentId = null; // ID of the comment being edited

    public function mount($announcementId)
    {
        $this->announcementId = $announcementId;
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = NewsReact::where('announcement_id', $this->announcementId)
            ->whereNotNull('comment')
            ->with('user')
            ->latest('created_at')
            ->get();

        $this->commentCount = $this->comments->count();
    }

    public function addComment()
    {
        if (empty($this->comment)) return;

        if ($this->editCommentId) {
            // Update existing comment
            $existingComment = NewsReact::find($this->editCommentId);
            if ($existingComment) {
                $existingComment->update(['comment' => $this->comment]);
                
                // Log activity
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'activity' => 'Edited a comment: ' . $this->comment
                ]);
            }

            $this->editCommentId = null; // Reset edit mode
        } else {
            // Create new comment
            NewsReact::create([
                'announcement_id' => $this->announcementId,
                'user_id' => Auth::id(),
                'comment' => $this->comment
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Added a new comment: ' . $this->comment
            ]);
        }

        $this->comment = '';
        $this->loadComments();
        $this->showCommentForm = false; // Hide modal after comment is added
    }

    public function editComment($commentId)
    {
        $comment = NewsReact::find($commentId);
        if ($comment && $comment->user_id === Auth::id()) {
            $this->comment = $comment->comment;
            $this->editCommentId = $commentId; // Set the comment ID to be edited
            $this->showCommentForm = true; // Ensure modal is shown for editing
        }
    }

    public function deleteComment($commentId)
    {
        $comment = NewsReact::find($commentId);
        if ($comment && $comment->user_id === Auth::id()) {
            $comment->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Deleted a comment: ' . $comment->comment
            ]);

            $this->loadComments();
        }
    }

    public function toggleModal()
    {
        $this->showCommentForm = !$this->showCommentForm;
    }

    public function render()
    {
        return view('livewire.comment-section');
    }
}
