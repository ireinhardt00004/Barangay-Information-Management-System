<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\UserInfo;
class UserRegistrationController extends Controller
{
    //
    public function deleteResident(Request $request)
{
    $authuser = auth()->user();
    if ($authuser->roles !== 'admin') {
        abort(403, 'Unauthorized');
    }
    $userId = $request->input('uid');
    $userType = $request->input('user_type');

    // Check if user exists and is of type 'resident'
    $user = User::where('id', $userId)->where('roles', 'resident')->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found or not a resident member.']);
    }

    // Log the activity
    $fullName = $user->fname . ' ' . $user->middlename . ' ' . $user->lname;
    ActivityLog::create([
        'user_id' => auth()->user()->id,
        'activity' => 'Deleted a resident account named, ' . $fullName
    ]);

    // Delete the user
    $user->forcedelete();

    return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
}

    public function deleteStaff(Request $request)
    {
        $authuser = auth()->user();
        if ($authuser->roles !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $userId = $request->input('uid');
        $userType = $request->input('user_type');

        // Check if user exists and is of type 'Staff'
        $user = User::where('id', $userId)->where('roles', 'staff')->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found or not a staff member.']);
        }
         // Log the activity
        $fullName = $user->fname . ' ' . $user->middlename . ' ' . $user->lname;
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Deleted a staff account named, ' . $fullName
        ]);


        // Delete the user
        $user->forcedelete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    public function storeStaff(Request $request): RedirectResponse
{
    $authuser = auth()->user();
    if ($authuser->roles !== 'admin') {
        abort(403, 'Unauthorized');
    }
    $validatedData = $request->validate([
        'lname' => ['required', 'string', 'max:255'],
        'middlename' => ['nullable', 'string', 'max:255'],
        'fname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', Rules\Password::defaults()],
    ]);

    try {
        $user = User::create([
            'lname' => $validatedData['lname'],
            'middlename' => $validatedData['middlename'],
            'fname' => $validatedData['fname'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'roles' => 'staff',
            'verified' => true,
        ]);

        $uid = mt_rand(1000000000, 9999999999); 
        
        UserInfo::create([
            'user_id' => $user->id,
            'uid' => $uid,
            'profile_pic' => null,
        ]);

        // Log the activity
        $fullName = $user->fname . ' ' . $user->middlename . ' ' . $user->lname;
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Registered new staff account named, ' . $fullName
        ]);

        return redirect()->back()->with('success', 'New Staff Registered Successfully.');
    } catch (\Exception $e) {
        Log::error('Error occurred while registering staff: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e
        ]);
        return redirect()->back()->with('error', 'An error occurred while registering the staff.');
    }
}

}