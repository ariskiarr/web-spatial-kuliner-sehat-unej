<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600">Logged in as <span class="font-semibold">{{ Auth::user()->email }}</span></p>
                        <p class="text-sm text-gray-500 mt-1">Role: <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">{{ Auth::user()->role }}</span></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
                        <a href="{{ route('profile.edit') }}" class="block p-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg hover:shadow-xl transition text-white">
                            <h4 class="font-bold text-lg mb-2">Edit Profile</h4>
                            <p class="text-sm text-blue-100">Update your information</p>
                        </a>

                        <div class="block p-6 bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg text-white">
                            <h4 class="font-bold text-lg mb-2">Browse Menu</h4>
                            <p class="text-sm text-green-100">Explore food options</p>
                        </div>

                        <div class="block p-6 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg text-white">
                            <h4 class="font-bold text-lg mb-2">Restaurants</h4>
                            <p class="text-sm text-purple-100">Find places to eat</p>
                        </div>

                        <div class="block p-6 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg shadow-lg text-white">
                            <h4 class="font-bold text-lg mb-2">My Favorites</h4>
                            <p class="text-sm text-pink-100">Your saved places</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
