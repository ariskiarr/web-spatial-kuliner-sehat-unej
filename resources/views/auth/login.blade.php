
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 via-gray-300 to-blue-900">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border border-gray-200">
        <h2 class="text-3xl font-bold text-center text-blue-900 mb-6">Login</h2>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                @endif
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-900 text-white font-bold rounded-lg shadow-md hover:bg-blue-800 transition">Log in</button>
        </form>
        <div class="mt-6 text-center">
            <a href="{{ route('register') }}" class="text-blue-700 hover:underline">Don't have an account? Register</a>
        </div>
    </div>
</div>
@endsection
