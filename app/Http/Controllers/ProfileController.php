<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
    
        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            // Validate the image file
            $request->validate([
                'profile_pic' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            // Delete old profile picture if exists
            if ($user->userinfos && $user->userinfos->profile_pic) {
                $oldImagePath = public_path('profile_pic/' . $user->userinfos->profile_pic);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
    
            // Store the new profile picture
            $profilePic = $request->file('profile_pic');
            $filename = time() . '_' . $profilePic->getClientOriginalName();
            $profilePic->move(public_path('profile_pic'), $filename);
    
            // Update or create UserInfo record
            $userInfo = $user->userinfos()->firstOrCreate();
            $userInfo->profile_pic = $filename;
            $userInfo->save();
        }
    
        // Update other user fields
        $user->fill($request->validated());
    
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    
        // Handle password change
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8',
            ]);
            $user->password = bcrypt($request->input('password'));
        }
    
        $user->save();
    
        return redirect()->back()->with('status', 'profile-updated');
    }
    
    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
