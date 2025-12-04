<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard - Peta Tempat Makan') }}
            </h2>
            <a href="{{ route('admin.tempat-makan.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Kelola Tempat Makan
            </a>
        </div>
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Card -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Tip:</strong> Klik pada peta untuk menambahkan tempat makan baru di lokasi tersebut!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Peta Interaktif</h3>
                    <div id="map" class="w-full rounded-lg border-2 border-gray-300" style="height: 600px;"></div>

                    <!-- Statistics -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="text-sm text-green-600 font-semibold">Total Tempat Makan</div>
                            <div class="text-2xl font-bold text-green-700">{{ $tempatMakan->count() }}</div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="text-sm text-blue-600 font-semibold">Area</div>
                            <div class="text-2xl font-bold text-blue-700">Universitas Jember</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                            <div class="text-sm text-purple-600 font-semibold">Status</div>
                            <div class="text-2xl font-bold text-purple-700">Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <script>
        // Initialize map centered on Universitas Jember
        const map = L.map('map').setView([-8.1706, 113.7026], 14);

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Custom icon for restaurant markers
        const restaurantIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Add existing restaurant markers
        const tempatMakan = @json($tempatMakan);

        tempatMakan.forEach(function(tempat) {
            if (tempat.latitude && tempat.longitude) {
                const marker = L.marker([tempat.latitude, tempat.longitude], {
                    icon: restaurantIcon
                }).addTo(map);

                // Create popup content
                const popupContent = `
                    <div style="min-width: 200px;">
                        <h3 style="font-weight: bold; font-size: 16px; margin-bottom: 8px;">${tempat.nama_tempat}</h3>
                        <p style="margin-bottom: 4px;"><strong>Kategori:</strong> ${tempat.nama_kategori}</p>
                        <p style="margin-bottom: 8px;"><strong>Alamat:</strong> ${tempat.alamat}</p>
                        <div style="display: flex; gap: 8px;">
                            <a href="/admin/tempat-makan/${tempat.id}/edit"
                               style="background-color: #3b82f6; color: white; padding: 4px 12px; border-radius: 4px; text-decoration: none; font-size: 12px;">
                               Edit
                            </a>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
            }
        });

        // Temporary marker for new location
        let tempMarker = null;

        // Handle map click - Add new restaurant
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            // Remove temporary marker if exists
            if (tempMarker) {
                map.removeLayer(tempMarker);
            }

            // Add temporary marker with green color
            const greenIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            tempMarker = L.marker([lat, lng], { icon: greenIcon }).addTo(map);

            // Create popup with action buttons
            const popupContent = `
                <div style="min-width: 200px; text-align: center;">
                    <h3 style="font-weight: bold; font-size: 14px; margin-bottom: 8px;">Tambah Tempat Makan Baru</h3>
                    <p style="font-size: 12px; color: #666; margin-bottom: 8px;">
                        Lat: ${lat.toFixed(6)}<br>
                        Lng: ${lng.toFixed(6)}
                    </p>
                    <a href="/admin/tempat-makan/create?latitude=${lat.toFixed(6)}&longitude=${lng.toFixed(6)}"
                       style="display: inline-block; background-color: #10b981; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: bold;">
                       + Tambah di Sini
                    </a>
                </div>
            `;

            tempMarker.bindPopup(popupContent).openPopup();
        });

        // Add scale control
        L.control.scale().addTo(map);
    </script>
</x-app-layout>
