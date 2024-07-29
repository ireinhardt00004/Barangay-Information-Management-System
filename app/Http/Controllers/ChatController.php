<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    // Render the chat view
    public function index()
    {
        return view('chats.message');
    }
    public function resident()
    {
        return view('chats.chat');
    }
    public function deleteConversation($receiverId)
    {
        $authId = Auth::id();

        Chat::where(function ($query) use ($authId, $receiverId) {
            $query->where('sender_id', $authId)
                  ->where('receiver_id', $receiverId);
        })
        ->orWhere(function ($query) use ($authId, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $authId);
        })
        ->forcedelete();

        // return response()->json(['status' => 'success']);
        return redirect()->back()->with('success','Deleted Successfully');
    }
    // Fetch chats for the sidebar with seen status
    public function fetchChats(Request $request)
    {
        $memberId = $request->input('member_id');

        // Fetch chats where the current user is either the sender or receiver
        $chats = Chat::where(function($query) use ($memberId) {
                            $query->where('sender_id', $memberId)
                                  ->orWhere('receiver_id', $memberId);
                        })
                        ->select('sender_id', 'receiver_id', DB::raw('MAX(seen) as seen'), DB::raw('MAX(message) as message'))
                        ->groupBy('sender_id', 'receiver_id')
                        ->get();
                        
        return response()->json($chats);
    }

    // Fetch messages between the current user and selected user
    public function fetchMessages(Request $request)
    {
        $memberId = $request->input('member_id');

        $messages = Chat::where(function($query) use ($memberId) {
                            $query->where('sender_id', Auth::id())
                                  ->where('receiver_id', $memberId);
                        })
                        ->orWhere(function($query) use ($memberId) {
                            $query->where('sender_id', $memberId)
                                  ->where('receiver_id', Auth::id());
                        })
                        ->orderBy('created_at')
                        ->get();

        // Mark messages as seen for the current user
        Chat::where('receiver_id', Auth::id())
            ->where('sender_id', $memberId)
            ->update(['seen' => 1]);

        return response()->json($messages);
    }

    // Send a new message
    public function sendMessage(Request $request)
    {
        $message = new Chat();
        $message->sender_id = Auth::id();
        $message->receiver_id = $request->input('receiver_id');
        $message->message = $request->input('message');
        $message->seen = 0; // Initially, mark as not seen
        $message->save();

        return response()->json(['success' => true]);
    }

    // Search for users by query with delay to prevent overwhelming the server
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');

        sleep(1); // Delay to prevent overwhelming the server

        try {
            \Log::info('Search Query:', ['query' => $query]);

            $users = User::where(function($q) use ($query) {
                $q->where('lname', 'like', "%$query%")
                  ->orWhere('fname', 'like', "%$query%")
                  ->orWhere('middlename', 'like', "%$query%")
                  ->orWhere(function($q) use ($query) {
                      $q->where(DB::raw("concat_ws(' ', fname, middlename)"), 'like', "%$query%")
                        ->where('lname', 'like', "%$query%");
                  });
            })->get();

            \Log::info('Search Results:', ['results' => $users]);

            return response()->json($users); 
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
