<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\UserInfo;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\ReportRequest;
use Illuminate\View\View; 
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountVerified;
use App\Mail\RegistrationDeclined;
use Exception;
use Log;

class AdminController extends Controller
{
    public function showDashboard()
{
    $user = auth()->user();
    if ($user->roles !== 'admin') {
        abort(403, 'Unauthorized');
    }

    // Initialize an array for all 12 months with 0 values
    $monthly_requests = array_fill(0, 12, 0);

    // Fetch data from the database and populate the array
    $requests = Service::selectRaw('MONTH(created_at) as month, COUNT(id) as total_requests')
        ->groupByRaw('MONTH(created_at)')
        ->pluck('total_requests', 'month')
        ->toArray();

    
    foreach ($requests as $month => $count) {
        $monthly_requests[$month - 1] = $count;
    }

    $male_count = UserInfo::where('sex', 'Male')->count();
    $female_count = UserInfo::where('sex', 'Female')->count();
    $uncat_count = UserInfo::whereNull('sex')->count();

    // Log activity
    ActivityLog::create([
        'user_id' => auth()->user()->id,
        'activity' => 'Visited Admin Dashboard page.'
    ]);

    // Pass data to the view
    return view('admin.dashboard', compact('monthly_requests', 'male_count', 'female_count', 'uncat_count'));
}

    
    public function viewStaff() {
        $user = auth()->user();
        if ($user->roles !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $users = User::where('roles', 'Staff')
            ->get()
            ->map(function ($user) {
                return [
                    'Firstname' => $user->fname,
                    'Lastname' => $user->lname,
                    'Email' => $user->email,
                    'Date created' => $user->created_at->format('Y-m-d'),
                    'Action' => '<div id="' . $user->id . '" class="w-100 d-flex justify-content-center">
                                    <button class="mx-1 btn btn-sm btn-outline-danger act-btn" onclick="delete_staff(1,\'' . $user->id . '\');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                 </div>',
                ];
            });
            ActivityLog::create([
                'user_id' => auth()->user()->id,
                'activity' => 'Visited View Staff page.'
            ]);

        return view('admin.staff', compact('users'));
    }
    
    public function viewResidents()
{
    $admin = auth()->user();
        if ($admin->roles !== 'admin') {
            abort(403, 'Unauthorized');
        }
    $users = User::where('roles', 'resident')
        ->get()
        ->map(function ($user) {
            return [
                'Firstname' => $user->fname,
                'Lastname' => $user->lname,
                'Email' => $user->email,
                'Date created' => $user->created_at->format('Y-m-d'),
                'Action' => '<div id="' . $user->id . '" class="w-100 d-flex justify-content-center">
                                <button class="mx-1 btn btn-sm btn-outline-danger act-btn" onclick="delete_resident(\'' . $user->id . '\');">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                             </div>',
            ];
        });
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited View Residents page.'
        ]);
    return view('admin.resident', compact('users'));
}
    public function viewSettings()
    {
    $user = auth()->user();
    if ($user->roles !== 'admin') {
        abort(403, 'Unauthorized');
    }
    $userInfo = $user->userinfos()->first(); 
    ActivityLog::create([
        'user_id' => auth()->user()->id,
        'activity' => 'Visited Admin Settings page.'
    ]);
    return view('admin.settings', [
        'user_photo' => $userInfo ? $userInfo->profile_pic : 'default.jpg', // Handle case where userInfo is null
        'firstname' => $user->fname,
        'middlen' => $user->mname,
        'lastname' => $user->lname,
        'email' => $user->email,
    ]);
}
    
    public function viewRequest()
    {
        $user = auth()->user();
        if (!in_array($user->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $logs = Service::with(['users', 'modifiedBy'])->latest()->get();
        $formattedLogs = $logs->map(function($log) {
            return [
                'users' => $log->users->email ?? 'N/A',
                'modifiedBy' => $log->modifiedBy->email ?? 'N/A',
                'tracking_code' => $log->tracking_code,
                'status' => $log->status,
                'updated_at' => $log->updated_at->format('F j, Y g:i A') // Format date as Month Day, Year 12-hour format
            ];
        });

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited View Request Logs page.'
        ]);
        return view('admin.request', ['logs' => $formattedLogs]);
    }
    public function viewReport() {
        $reports = ReportRequest::latest()->get();

        // Transform the data to a format suitable for the front-end
        $transformedReports = $reports->map(function($report) {
            return [
                'fullname' => base64_decode($report->fullname),
                'email' => base64_decode($report->email),
                'contact_num' => base64_decode($report->contact_num),
                'issue' => base64_decode($report->issue),
                'status' => $report->status,
                'created_at' => \Carbon\Carbon::parse($report->created_at)->format('F j, Y g:i A'),
                'id' => $report->id,
            ];
        });

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited View Reports page.'
        ]);

        return view('admin.report', ['reports' => $transformedReports]);
    }


public function showUnverifiedUsers()
{
    $authUser = auth()->user();
    if (!in_array( $authUser->roles, ['admin', 'staff'])) {
        abort(403, 'Unauthorized');
    }
    $users = User::where('verified', false)
                ->where('roles', 'resident')
                ->get();
    $userData = $users->map(function ($user) {
        return [
            'uid' => $user->uid,
            'fullname' => $user->fname . ' ' . $user->middlename . ' ' . $user->lname,
            'email' => $user->email,
            'valid_id' => $user->userinfos->valid_id,
            'actions' => '
                <button class="btn btn-sm btn-outline-primary" onclick="view_id(\'' . $user->userinfos->valid_id . '\')"><i class="fa-regular fa-eye"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="mod_request(2, \'' . $user->id . '\')"><i class="fa-solid fa-x"></i></button>
                <button class="btn btn-sm btn-outline-success" onclick="mod_request(3, \'' . $user->id . '\')"><i class="fa-solid fa-check"></i></button>
            ',
        ];
    });

    ActivityLog::create([
        'user_id' => auth()->user()->id,
        'activity' => 'Visited Validate Account page.'
    ]);

    return view('admin.unverified-users', ['users' => $userData]);
}
    public function approveUser(int $id)
{
    $authUser = auth()->user();
    if (!in_array( $authUser->roles, ['admin', 'staff'])) {
        abort(403, 'Unauthorized');
    }
    \Log::info("Approve User called for ID: {$id}");   
    $user = User::findOrFail($id);
    $fullName = trim($user->fname .' '. $user->middlename .' '. $user->lname);

    ActivityLog::create([
        'user_id' => auth()->user()->id,
        'activity' => 'Verified the account of ' . $fullName,
    ]);

    $user->update(['verified' => true]);
    \Log::info("User approved successfully: ", $user->toArray());

    // Send verification email
    try {
        Mail::to($user->email)->send(new AccountVerified($user));
    } catch (\Exception $e) {
        \Log::error('Error in approving user: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to send verification email.']);
    }

    return response()->json(['success' => true, 'message' => 'User verified successfully.']);
}
    public function declineUser(int $id)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
    Log::info("Decline User called for ID: {$id}");

    try {
        $user = User::findOrFail($id);
        $fullName = trim($user->fname .' '. $user->middlename .' '. $user->lname);

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Declined the account of ' . $fullName,
        ]);
        // Send decline notification email
        try {
            Mail::to($user->email)->send(new RegistrationDeclined($user));
        } catch (Exception $e) {
            Log::error('Error in sending decline email: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send decline notification email.']);
        }
        // Delete the user
        $user->forceDelete(); 
        Log::info("User declined successfully: ", $user->toArray());

        return response()->json(['success' => true, 'message' => 'User declined successfully.']);
    } catch (Exception $e) {
        Log::error("Error in declining user: {$e->getMessage()}");
        return response()->json(['success' => false, 'message' => 'User decline failed.']);
    }
}

}
