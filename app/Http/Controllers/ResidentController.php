<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Program;
use App\Models\Service;
use App\Models\Event;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    //
    public function index() {
        $user = auth()->user();
        if ($user->roles !== 'resident') {
            abort(403, 'Unauthorized');
        }

        $userID = $user->id;
        $usersReq = Service::where('user_id', $userID)->get();
        $announcements = Announcement::latest()->get();
        
        foreach ($announcements as $announcement) {
            $announcement->decoded_title = base64_decode($announcement->title);
            $announcement->decoded_content = base64_decode($announcement->content);
            $announcement->decoded_cover = base64_decode($announcement->cover);
        }

        // Fetch and count request statuses
        $statusCounts = Service::where('user_id', $userID)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusCounts = array_merge([
            'approved' => 0,
            'pending' => 0,
            'declined' => 0
        ], $statusCounts);
        $events = Event::all();
        $calendarEvents = Event::all()->map(function ($event) {
            return [
                'title' => $event->title,
                'start' => $event->start_datetime,
                'end' => $event->end_datetime,
                'description' => $event->description,
                'type' => $event->type,
            ];
        });

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited User Dashboard page.',
        ]);

        return view('resident.index', compact('announcements', 'events', 'usersReq', 'statusCounts', 'calendarEvents'));
    }
    
    public function news()
    {
        $announcements = Announcement::latest()->get();
        foreach ($announcements as $announcement) {
            $announcement->decoded_title = base64_decode($announcement->title);
            $announcement->decoded_content = base64_decode($announcement->content);
            $announcement->decoded_cover = base64_decode($announcement->cover);
        }
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited  User News and Announcement  page.',
        ]);
        return view('resident.news', compact('announcements'));
    }
    public function program() {
        $user = auth()->user();
        if ($user->roles !== 'resident') {
            abort(403, 'Unauthorized');
        }
        $result = Program::all();
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited  User Program  page.',
        ]);
        return view('resident.userprog', compact('result'));
    }
    public function viewProgram($id)
    {
    $res = Program::findOrFail($id);

    // Decode the base64 ENcyrption
    $res->cover = base64_decode($res->cover);
    $res->title = base64_decode($res->title);
    $res->content = base64_decode($res->content);
    
    return view('resident.viewprog', compact('res'));
    }

    public function settings(){

    $user = auth()->user();
    if ($user->roles !== 'resident') {
        abort(403, 'Unauthorized');
    }
    $userInfo = $user->userinfos()->first(); 
    ActivityLog::create([
        'user_id' => auth()->user()->id,
        'activity' => 'Visited  User Settings page.'
    ]);
    return view('resident.settings', [
        'user_photo' => $userInfo ? $userInfo->profile_pic : 'default.jpg', // Handle case where userInfo is null
        'firstname' => $user->fname,
        'middlen' => $user->mname,
        'lastname' => $user->lname,
        'email' => $user->email,
    ]);

    }
    
}
