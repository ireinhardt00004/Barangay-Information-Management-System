@extends('layouts.app')

@section('content')
@include('layouts.navs')

<div style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div style="max-width: 28rem; width: 100%; background-color: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden; padding: 1.5rem;">
        <h3 class="text-center"><i class="fas fa-key"></i> Reset Password</h3>
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div style="margin-bottom: 1rem;">
                <label for="email" style="display: block; font-weight: 500; font-size: 0.875rem; color: #4a5568;">{{ __('Email') }}</label>
                <input id="email" class="block mt-1 w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" style="display: block; margin-top: 0.25rem; width: 100%; border-radius: 0.375rem; padding: 0.5rem; background-color: #f7fafc; border: 1px solid #e2e8f0; color: #1a202c;">
                @error('email')
                    <span style="margin-top: 0.5rem; font-size: 0.875rem; color: #e53e3e;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-4" style="margin-bottom: 1rem;">
                <label for="password" style="display: block; font-weight: 500; font-size: 0.875rem; color: #4a5568;">{{ __('Password') }}</label>
                <input id="password" class="block mt-1 w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" type="password" name="password" required autocomplete="new-password" style="display: block; margin-top: 0.25rem; width: 100%; border-radius: 0.375rem; padding: 0.5rem; background-color: #f7fafc; border: 1px solid #e2e8f0; color: #1a202c;">
                @error('password')
                    <span style="margin-top: 0.5rem; font-size: 0.875rem; color: #e53e3e;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mt-4" style="margin-bottom: 1rem;">
                <label for="password_confirmation" style="display: block; font-weight: 500; font-size: 0.875rem; color: #4a5568;">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" class="block mt-1 w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" type="password" name="password_confirmation" required autocomplete="new-password" style="display: block; margin-top: 0.25rem; width: 100%; border-radius: 0.375rem; padding: 0.5rem; background-color: #f7fafc; border: 1px solid #e2e8f0; color: #1a202c;">
                @error('password_confirmation')
                    <span style="margin-top: 0.5rem; font-size: 0.875rem; color: #e53e3e;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 1rem;">
                <button style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #3182ce; border: none; border-radius: 0.375rem; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; color: white; cursor: pointer; transition: background-color 0.2s;">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('title','Reset Password')