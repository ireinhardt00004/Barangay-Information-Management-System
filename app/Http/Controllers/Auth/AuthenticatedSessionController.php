<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Visit;
use Carbon\Carbon;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        // Check if a visit entry for the user exists, and create one if not
    $visit = Visit::firstOrCreate(
        ['user_id' => Auth::id()],
        ['login' => now()] // Set the current timestamp for login if the visit is newly created
    );
    // Log login activity
    ActivityLog::create([
        'user_id' =>  Auth::user()->id,
        'activity' => 'Logged in',
    ]);

    // Update the login time if the visit entry already exists
    if (!$visit->wasRecentlyCreated) {
        $visit->update(['login' => now()]);
    }
    if (Auth::check()) {
        $roles = Auth::user()->roles;
        
        if ($roles === 'resident') {
            $user_id = Auth::user()->id;
            Session::put('user_id', $user_id);
            return redirect()->route('userdashboard.index');
        } elseif ($roles === 'admin') {
            return redirect()->route('dashboard');
        } elseif ($roles === 'staff') {
            return redirect()->route('staff.dashboard');           
        }    
}

    return redirect(RouteServiceProvider::HOME);
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            $userId = Auth::id();
    
            Auth::guard('web')->logout();
    
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
        // Record the logout event in the "visits" table
        Visit::where('user_id', $userId)
                ->whereNull('logout') // Ensure we only update if logout is not set already
                ->update(['logout' => now()]); // Set the current timestamp for logout
        
        // Record the logout event in the activity log
        ActivityLog::create([
            'user_id' => $userId,
            'activity' => 'Logged out',
        ]);
    }
        return redirect('/');
    }
}
