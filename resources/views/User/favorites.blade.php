<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tempat Makan Favorit') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">❤️ Daftar Favorit Saya</h3>
                    <span class="text-sm text-gray-600">{{ $favorites->count() }} tempat</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($favorites as $favorite)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition">
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $favorite->nama_tempat }}</h4>
                                    <button onclick="removeFavorite({{ $favorite->tempat_id }}, '{{ $favorite->nama_tempat }}')" class="text-red-500 hover:text-red-700 transition">
                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </button>
                                </div>

                                <p class="text-sm text-gray-600 mb-2">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $favorite->alamat }}
                                </p>

                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                        {{ $favorite->nama_kategori }}
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        {{ number_format($favorite->distance_km, 2) }} km
                                    </span>
                                </div>

                                <div class="mt-3 flex items-center justify-between">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= ($favorite->avg_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">{{ number_format($favorite->avg_rating ?? 0, 1) }}</span>
                                    </div>
                                    <span class="text-xs text-green-600 font-medium">~{{ number_format($favorite->avg_kalori, 0) }} kkal</span>
                                </div>

                                <a href="{{ route('user.tempat.show', $favorite->tempat_id) }}" class="mt-4 block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <p class="text-gray-600 text-lg mb-4">Belum ada tempat makan favorit</p>
                            <a href="{{ route('dashboard.user') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                                Jelajahi Tempat Makan
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <x-delete-confirmation-modal />

    <script>
        let selectedTempatId = null;
        let selectedTempatName = '';

        function removeFavorite(tempatId, tempatName) {
            selectedTempatId = tempatId;
            selectedTempatName = tempatName;
            document.getElementById('tempatName').textContent = tempatName;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function confirmDelete() {
            if (!selectedTempatId) return;

            fetch(`/user/tempat-makan/${selectedTempatId}/favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                closeModal();
            });
        }
    </script>
</x-app-layout>
