<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard - Tempat Makan Kalori Rendah') }}
        </h2>
    </x-slot>

    <!-- Leaflet CSS (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}" />
        <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">🔍 Filter Pencarian</h3>
                <form id="filterForm" method="GET" action="{{ route('dashboard.user') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Max Kalori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Maksimal Kalori (rata-rata)
                        </label>
                        <select name="max_calories" id="maxCalories" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="300" {{ $maxCalories == 300 ? 'selected' : '' }}>≤ 300 kkal (Rendah)</option>
                            <option value="500" {{ $maxCalories == 500 ? 'selected' : '' }}>≤ 500 kkal (Sedang)</option>
                            <option value="700" {{ $maxCalories == 700 ? 'selected' : '' }}>≤ 700 kkal (Tinggi)</option>
                            <option value="10000" {{ $maxCalories == 10000 ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>

                    <!-- Radius -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Radius dari Kampus
                        </label>
                        <select name="radius" id="radius" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="1" {{ $radius == 1 ? 'selected' : '' }}>1 km</option>
                            <option value="2" {{ $radius == 2 ? 'selected' : '' }}>2 km</option>
                            <option value="5" {{ $radius == 5 ? 'selected' : '' }}>5 km</option>
                            <option value="10" {{ $radius == 10 ? 'selected' : '' }}>10 km</option>
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori Tempat
                        </label>
                        <select name="kategori_id" id="kategoriId" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ $kategoriId == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Map and List Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Map -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800">🗺️ Peta Interaktif</h3>
                        </div>
                        <div id="map" class="w-full h-[600px]"></div>
                    </div>
                </div>

                <!-- List Tempat Makan -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 max-h-[650px] overflow-y-auto">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            📍 Daftar Tempat Makan
                            <span class="text-sm font-normal text-gray-500">({{ count($tempatMakans) }} hasil)</span>
                        </h3>

                        <div id="tempatList" class="space-y-4">
                            @forelse($tempatMakans as $tempat)
                                <a href="{{ route('user.tempat.show', $tempat->id) }}" class="block border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-blue-300 transition tempat-item"
                                     data-id="{{ $tempat->id }}"
                                     data-lat="{{ $tempat->lat }}"
                                     data-lng="{{ $tempat->lng }}">
                                    <h4 class="font-semibold text-gray-900">{{ $tempat->nama_tempat }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $tempat->alamat }}</p>
                                    <div class="mt-3 flex items-center justify-between text-sm">
                                        <span class="text-blue-600 font-medium">
                                            <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            {{ number_format($tempat->distance_km, 2) }} km
                                        </span>
                                        <span class="text-green-600 font-medium">
                                            <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            ~{{ number_format($tempat->avg_kalori, 0) }} kkal
                                        </span>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        <span class="bg-gray-100 px-2 py-1 rounded">{{ $tempat->nama_kategori ?? 'Lainnya' }}</span>
                                        <span class="ml-2">{{ $tempat->total_menu }} menu</span>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <span class="text-xs text-blue-600 font-medium">Klik untuk detail →</span>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="mx-auto w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                                    </svg>
                                    <p>Tidak ada tempat makan yang sesuai filter</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Leaflet JS (Local) -->
    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>

    <script>
        // Fix marker icon paths for local assets
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: '{{ asset("assets/leaflet/images/marker-icon-2x.png") }}',
            iconUrl: '{{ asset("assets/leaflet/images/marker-icon.png") }}',
            shadowUrl: '{{ asset("assets/leaflet/images/marker-shadow.png") }}',
        });

        // Initialize map
        const kampusLat = {{ $kampus->latitude }};
        const kampusLng = {{ $kampus->longitude }};
        const radius = {{ $radius }};

        const map = L.map('map').setView([kampusLat, kampusLng], 14);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Add kampus marker (Red - Larger)
        const kampusIcon = L.icon({
            iconUrl: '{{ asset("assets/leaflet/images/marker-icon-2x-red.png") }}',
            shadowUrl: '{{ asset("assets/leaflet/images/marker-shadow.png") }}',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.marker([kampusLat, kampusLng], { icon: kampusIcon })
            .addTo(map)
            .bindPopup('<div class="p-2"><b>🏫 Kampus</b><br><p class="text-sm">{{ $kampus->alamat }}</p></div>');

        // Add radius circle
        const circle = L.circle([kampusLat, kampusLng], {
            color: '#3b82f6',
            fillColor: '#93c5fd',
            fillOpacity: 0.15,
            radius: radius * 1000,
            weight: 2
        }).addTo(map);

        // Add tempat makan markers
        const tempatMarkers = {};
        @foreach($tempatMakans as $tempat)
            const marker{{ $tempat->id }} = L.marker([{{ $tempat->lat }}, {{ $tempat->lng }}])
                .addTo(map)
                .bindPopup(`
                    <div class="p-2">
                        <h3 class="font-bold text-base">{{ $tempat->nama_tempat }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $tempat->alamat }}</p>
                        <div class="mt-2 space-y-1">
                            <p class="text-sm">
                                <span class="text-blue-600 font-medium">📍 {{ number_format($tempat->distance_km, 2) }} km dari kampus</span>
                            </p>
                            <p class="text-sm">
                                <span class="text-green-600 font-medium">🍽️ Rata-rata: {{ number_format($tempat->avg_kalori, 0) }} kkal</span>
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $tempat->nama_kategori }} • {{ $tempat->total_menu }} menu
                            </p>
                        </div>
                    </div>
                `);
            tempatMarkers[{{ $tempat->id }}] = marker{{ $tempat->id }};
        @endforeach

        // Click handler for tempat list
        document.querySelectorAll('.tempat-item').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.dataset.id;
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.lng);

                // Zoom to marker
                map.setView([lat, lng], 16);

                // Open popup
                if (tempatMarkers[id]) {
                    tempatMarkers[id].openPopup();
                }

                // Highlight selected item
                document.querySelectorAll('.tempat-item').forEach(i => {
                    i.classList.remove('border-blue-500', 'bg-blue-50');
                });
                this.classList.add('border-blue-500', 'bg-blue-50');
            });
        });

        // Fit bounds to show all markers
        @if(count($tempatMakans) > 0)
            const bounds = L.latLngBounds([
                [kampusLat, kampusLng],
                @foreach($tempatMakans as $tempat)
                    [{{ $tempat->lat }}, {{ $tempat->lng }}],
                @endforeach
            ]);
            map.fitBounds(bounds, { padding: [50, 50] });
        @endif
    </script>
</x-app-layout>
