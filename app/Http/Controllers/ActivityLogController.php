<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function useractivityLog() {
        $userID = auth()->user()->id;
    
        // Fetch logs with related user data
        $logs = ActivityLog::with('user')
            ->where('user_id', $userID)
            ->latest()
            ->paginate(50);
        $logs->getCollection()->transform(function ($log) {
            $user = $log->user;
            $name = $user ? ($user->fname . ' ' . $user->middlename . ' ' . $user->lname) : 'Unknown User';
    
            return [
                'name' => $name,
                'activity' => $log->activity,
                'timestamp' => Carbon::parse($log->created_at)->diffForHumans(),
                'formatted_timestamp' => Carbon::parse($log->created_at)->format('m/d/Y h:i A'),
            ];
        });
    
        // Pass the transformed logs to the view
        return view('activity.user', ['logs' => $logs]);
    }
    public function deleteuserLogs() {
        $userID = auth()->user()->id;
        ActivityLog::where('user_id', $userID)->delete();
    
        // Redirect with success message
        return redirect()->route('my-useractivity')->with('success', 'All logs have been deleted successfully.');
    }
    public function activityLog() {
        $userID = auth()->user()->id;
    
        // Fetch logs with related user data
        $logs = ActivityLog::with('user')
            ->where('user_id', $userID)
            ->latest()
            ->paginate(50);
        $logs->getCollection()->transform(function ($log) {
            $user = $log->user;
            $name = $user ? ($user->fname . ' ' . $user->middlename . ' ' . $user->lname) : 'Unknown User';
    
            return [
                'name' => $name,
                'activity' => $log->activity,
                'timestamp' => Carbon::parse($log->created_at)->diffForHumans(),
                'formatted_timestamp' => Carbon::parse($log->created_at)->format('m/d/Y h:i A'),
            ];
        });
    
        // Pass the transformed logs to the view
        return view('activity.index', ['logs' => $logs]);
    }
    public function deleteLogs() {
        $userID = auth()->user()->id;
        ActivityLog::where('user_id', $userID)->delete();
    
        // Redirect with success message
        return redirect()->route('my-activity')->with('success', 'All logs have been deleted successfully.');
    }
    public function adminLogs() {
        $user = auth()->user();
        if ($user->roles !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(50);

        $logs->getCollection()->transform(function ($log) {
            return [
                'name' => $log->user->roles. ' ' .$log->user->fname . ' ' . $log->user->middlename . ' ' . $log->user->lname,
                'activity' => $log->activity,
                'timestamp' => Carbon::parse($log->created_at)->diffForHumans(),
                'formatted_timestamp' => Carbon::parse($log->created_at)->format('m/d/Y h:i A')
            ];
        });

        return view('admin.all-logs', compact('logs'));
    }

    public function deleteAll()
    {
    $user = auth()->user();
    if ($user->roles !== 'admin') {
        abort(403, 'Unauthorized');
    }
    ActivityLog::truncate();
    return redirect()->route('all-logs')->with('success', 'All logs have been deleted successfully.');
    }

}
