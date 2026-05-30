






@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 via-gray-300 to-blue-900">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border border-gray-200">
        <h2 class="text-3xl font-bold text-center text-blue-900 mb-6">Register</h2>
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-blue-50 placeholder-gray-400" placeholder="Your name" />
                @error('name')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-blue-50 placeholder-gray-400" placeholder="you@email.com" />
                @error('email')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-blue-50 placeholder-gray-400" placeholder="Password" />
                @error('password')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-blue-50 placeholder-gray-400" placeholder="Confirm password" />
                @error('password_confirmation')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-900 text-white font-bold rounded-lg shadow-md hover:bg-blue-800 transition">Register</button>
        </form>
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-blue-700 hover:underline text-base">Already registered? Login</a>
        </div>
    </div>
</div>
@endsection
