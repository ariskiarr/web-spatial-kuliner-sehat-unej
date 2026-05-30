<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $tempat->nama_tempat }}
            </h2>
            <a href="{{ route('dashboard.user') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                ← Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Info Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $tempat->nama_tempat }}</h3>
                                <p class="text-gray-600 mt-1">{{ $tempat->nama_kategori }}</p>
                            </div>
                            <button id="favoriteBtn" class="p-2 rounded-full hover:bg-gray-100 transition">
                                <svg class="w-6 h-6 {{ $isFavorite ? 'text-red-500 fill-current' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="text-gray-700">{{ $tempat->alamat }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-700">{{ $tempat->jam_operasional }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span class="text-gray-700">
                                    {{ number_format($tempat->distance_km, 2) }} km dari kampus
                                </span>
                            </div>
                        </div>

                        <!-- Rating Summary -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center">
                                        <span class="text-3xl font-bold text-gray-900">{{ number_format($avgRating, 1) }}</span>
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">{{ $totalReviews }} ulasan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menu List -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">📋 Daftar Menu</h4>

                        <div class="space-y-3">
                            @forelse($menus as $menu)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h5 class="font-semibold text-gray-900">{{ $menu->nama_menu }}</h5>
                                            <p class="text-sm text-gray-600 mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                                {{ $menu->kalori <= 300 ? 'bg-green-100 text-green-800' :
                                                   ($menu->kalori <= 500 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ number_format($menu->kalori, 0) }} kkal
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-8">Belum ada menu tersedia</p>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Add Review -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">✍️ Tulis Ulasan</h4>

                        <form action="{{ route('user.review.store', $tempat->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                <div class="flex items-center space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" class="rating-star text-gray-300 hover:text-yellow-400 transition" data-rating="{{ $i }}">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="5" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ulasan</label>
                                <textarea
                                    name="komentar"
                                    rows="4"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Bagikan pengalaman Anda..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>

                    <!-- Reviews List -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">💬 Ulasan Terbaru</h4>

                        <div class="space-y-4">
                            @forelse($reviews as $review)
                                <div class="border-b border-gray-200 pb-4 last:border-0">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $review->penulis ?? 'Anonymous' }}</p>
                                            <div class="flex items-center mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($review->tanggal)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $review->ulasan }}</p>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Belum ada ulasan</p>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        // Rating stars interaction
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                ratingInput.value = rating;

                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });

        // Favorite button
        document.getElementById('favoriteBtn').addEventListener('click', function() {
            fetch('{{ route("user.favorite.toggle", $tempat->id) }}', {
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
            });
        });
    </script>
</x-app-layout>
