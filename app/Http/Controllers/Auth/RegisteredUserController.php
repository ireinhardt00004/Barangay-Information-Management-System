<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Exception;
use App\Mail\RegistrationSuccess;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'lname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'fname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'valid_id' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        try {
            $user = User::create([
                'lname' => $validatedData['lname'],
                'middlename' => $validatedData['middlename'],
                'fname' => $validatedData['fname'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'roles' => 'resident',
                'verified' => false,
            ]);

            $uid = mt_rand(1000000000, 9999999999);

            // Handle file upload
            if ($request->hasFile('valid_id')) {
                $file = $request->file('valid_id');
                $filename = 'valid_id_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('resident_valid_id'), $filename);
            } else {
                $filename = null;
            }

            UserInfo::create([
                'user_id' => $user->id,
                'uid' => $uid,
                'profile_pic' => null,
                'valid_id' => $filename,
            ]);

            event(new Registered($user));

            // Send registration success email
            Mail::to($user->email)->send(new RegistrationSuccess($user));

            // Redirect to the success message view
            return redirect()->route('registration.success');
        } catch (Exception $e) {
            // Handle the exception (log it, notify admin, etc.)
            return redirect()->back()->withErrors(['error' => 'An error occurred during registration. Please try again.']);
        }
    }
}
