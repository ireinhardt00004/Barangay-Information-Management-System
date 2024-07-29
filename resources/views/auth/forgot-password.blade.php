@extends('layouts.app')

@section('content')
@include('layouts.navs')

<div style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div style="max-width: 28rem; width: 100%; background-color: white; dark:bg-gray-800; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden; padding: 1.5rem;">
        <h3><i class="fas fa-key"></i> Forgot Password</h3>
        <div style="margin-bottom: 1rem; color: #4a5568; dark:text-gray-400;">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>
        
        <!-- Session Status -->
        @if (session('status'))
            <div style="margin-bottom: 1rem; color: #48bb78; dark:text-green-400;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div style="margin-bottom: 1rem;">
                <label for="email" style="display: block; font-weight: 500; font-size: 0.875rem; color: #4a5568; dark:text-gray-300;">{{ __('Email') }}</label>
                <input id="email" style="display: block; margin-top: 0.25rem; width: 100%; border-radius: 0.375rem; shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 0.5rem; background-color: #f7fafc; dark:bg-gray-700; border: 1px solid #e2e8f0; dark:border-gray-600; color: #1a202c; dark:text-white;" type="email" name="email" value="{{ old('email') }}" required autofocus />
                @error('email')
                    <span style="margin-top: 0.5rem; font-size: 0.875rem; color: #e53e3e; dark:text-red-400;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 1rem;">
                <button style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #3182ce; border: none; border-radius: 0.375rem; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; color: white; cursor: pointer; transition: background-color 0.2s;">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('title', 'Forgot Password')
