<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - CalorieMaps</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            overflow: hidden;
        }

        /* Sidebar Styles */
        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 320px;
            height: 100vh;
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0,0,0,0.3);
        }

        #sidebar::-webkit-scrollbar {
            width: 8px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 4px;
        }

        #sidebar::-webkit-scrollbar-thumb:hover {
            background: #777;
        }

        /* Header in Sidebar */
        .sidebar-header {
            padding: 20px;
            background: #000;
            border-bottom: 2px solid #444;
        }

        .sidebar-header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #3b82f6;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .admin-badge svg {
            width: 14px;
            height: 14px;
        }

        /* Search Box */
        .search-box {
            padding: 15px 20px;
            background: #2d2d2d;
            border-bottom: 1px solid #444;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px;
            background: #1a1a1a;
            border: 1px solid #555;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            background: #222;
        }

        .search-input::placeholder {
            color: #888;
        }

        /* Filter Section */
        .filter-section {
            padding: 20px;
            border-bottom: 1px solid #444;
        }

        .filter-title {
            font-size: 14px;
            font-weight: 600;
            color: #aaa;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .filter-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            margin-bottom: 8px;
            background: #2d2d2d;
            border: 1px solid #444;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-item:hover {
            background: #3a3a3a;
            border-color: #666;
        }

        .filter-item.active {
            background: white;
            color: #1a1a1a;
            border-color: white;
            font-weight: 600;
        }

        .filter-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
        }

        /* Stats */
        .stats-section {
            padding: 20px;
            background: #2d2d2d;
            border-bottom: 1px solid #444;
        }

        .stat-card {
            background: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #3b82f6;
        }

        .stat-label {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        /* Action Buttons */
        .action-section {
            padding: 20px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 12px 15px;
            background: white;
            color: #1a1a1a;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            margin-bottom: 10px;
        }

        .action-btn:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.2);
        }

        .action-btn svg {
            width: 20px;
            height: 20px;
        }

        .logout-btn {
            background: #dc2626;
            color: white;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        /* Map Container */
        #map-container {
            position: fixed;
            left: 320px;
            top: 0;
            right: 0;
            bottom: 0;
            height: 100vh;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        /* Map Controls Overlay */
        .map-controls {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 999;
            display: flex;
            gap: 10px;
        }

        .control-btn {
            background: white;
            color: #1a1a1a;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .control-btn:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        /* Info Banner */
        .info-banner {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            z-index: 999;
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 500px;
        }

        .info-banner svg {
            width: 24px;
            height: 24px;
            color: #3b82f6;
            flex-shrink: 0;
        }

        .info-banner-text {
            font-size: 14px;
            color: #1a1a1a;
        }

        /* Custom Popup Styles */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .popup-content {
            min-width: 250px;
            padding: 5px;
        }

        .popup-header {
            font-size: 16px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .popup-info {
            margin-bottom: 8px;
            font-size: 13px;
            display: flex;
            align-items: start;
            gap: 8px;
        }

        .popup-info strong {
            color: #666;
            min-width: 80px;
        }

        .popup-hours {
            background: #10b981;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin: 8px 0;
        }

        .popup-buttons {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .popup-btn {
            flex: 1;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .popup-btn-edit {
            background: #3b82f6;
            color: white;
        }

        .popup-btn-edit:hover {
            background: #2563eb;
        }

        .popup-btn-menu {
            background: #10b981;
            color: white;
        }

        .popup-btn-menu:hover {
            background: #059669;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                width: 280px;
            }

            #map-container {
                left: 280px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <h1>
                <span class="logo-icon">🍴</span>
                CalorieMaps
            </h1>
            <div class="admin-badge">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                <span>Admin: {{ Auth::user()->name }}</span>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" placeholder="Cari tempat makan...">
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">Filter Kategori</div>
            <div class="filter-item active" data-category="all">
                <input type="checkbox" checked>
                <span>Semua Kategori ({{ $tempatMakan->count() }})</span>
            </div>
            @foreach($categories as $category)
            <div class="filter-item" data-category="{{ $category->id }}">
                <input type="checkbox">
                <span>{{ $category->nama_kategori }} ({{ $tempatMakan->where('kategori_id', $category->id)->count() }})</span>
            </div>
            @endforeach
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-label">Total Tempat Makan</div>
                <div class="stat-value">{{ $tempatMakan->count() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #10b981;">
                <div class="stat-label">Total Kategori</div>
                <div class="stat-value">{{ $categories->count() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <div class="stat-label">Area Coverage</div>
                <div class="stat-value" style="font-size: 16px;">Universitas Jember</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-section">
            <a href="{{ route('admin.tempat-makan.index') }}" class="action-btn">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z"/>
                </svg>
                Kelola Data Lengkap
            </a>
            <a href="{{ route('profile.edit') }}" class="action-btn" style="background: #6b7280; color: white;">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                Pengaturan Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="action-btn logout-btn">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Map Container -->
    <div id="map-container">
        <!-- Map Controls -->
        <div class="map-controls">
            <button class="control-btn" onclick="map.setView([-8.1706, 113.7026], 14)">
                <svg fill="currentColor" viewBox="0 0 20 20" style="width: 20px; height: 20px;">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                Reset View
            </button>
        </div>

        <!-- Info Banner -->
        <div class="info-banner">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="info-banner-text">
                <strong>Klik pada peta</strong> untuk menambahkan tempat makan baru di lokasi tersebut
            </div>
        </div>

        <!-- Map -->
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <script>
        // Initialize map centered on Universitas Jember
        const map = L.map('map').setView([-8.1706, 113.7026], 14);

        // Add OpenStreetMap tile layer (Google Maps style)
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

        // Store all markers
        const markers = [];
        const tempatMakan = @json($tempatMakan);

        // Add existing restaurant markers
        tempatMakan.forEach(function(tempat) {
            if (tempat.latitude && tempat.longitude) {
                const marker = L.marker([tempat.latitude, tempat.longitude], {
                    icon: restaurantIcon
                }).addTo(map);

                // Create popup content with jam operasional
                const jamOperasional = tempat.jam_operasional || 'Tidak tersedia';
                const popupContent = `
                    <div class="popup-content">
                        <div class="popup-header">${tempat.nama_tempat}</div>
                        <div class="popup-info">
                            <strong>Kategori:</strong>
                            <span>${tempat.nama_kategori}</span>
                        </div>
                        <div class="popup-info">
                            <strong>Alamat:</strong>
                            <span>${tempat.alamat}</span>
                        </div>
                        <div class="popup-info">
                            <strong>Jam Buka:</strong>
                            <span class="popup-hours">⏰ ${jamOperasional}</span>
                        </div>
                        <div class="popup-buttons">
                            <a href="/admin/tempat-makan/${tempat.id}/edit" class="popup-btn popup-btn-edit">
                                ✏️ Edit
                            </a>
                            <a href="/admin/tempat-makan/${tempat.id}/menu" class="popup-btn popup-btn-menu">
                                📋 Menu
                            </a>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);

                // Store marker with metadata for filtering
                markers.push({
                    marker: marker,
                    data: tempat
                });
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
                <div class="popup-content" style="text-align: center;">
                    <div class="popup-header">➕ Tambah Tempat Makan Baru</div>
                    <p style="font-size: 12px; color: #666; margin: 10px 0;">
                        Koordinat:<br>
                        <strong>Lat:</strong> ${lat.toFixed(6)}<br>
                        <strong>Lng:</strong> ${lng.toFixed(6)}
                    </p>
                    <a href="/admin/tempat-makan/create?latitude=${lat.toFixed(6)}&longitude=${lng.toFixed(6)}"
                       style="display: inline-block; background: #10b981; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 10px;">
                       🍴 Tambah di Sini
                    </a>
                </div>
            `;

            tempMarker.bindPopup(popupContent).openPopup();
        });

        // Add scale control
        L.control.scale({
            position: 'bottomleft',
            imperial: false
        }).addTo(map);

        // Filter functionality
        const filterItems = document.querySelectorAll('.filter-item');

        filterItems.forEach(item => {
            item.addEventListener('click', function() {
                const categoryId = this.dataset.category;
                const checkbox = this.querySelector('input[type="checkbox"]');

                // Toggle checkbox
                checkbox.checked = !checkbox.checked;

                // Toggle active class
                this.classList.toggle('active');

                // If "all" is clicked, uncheck others
                if (categoryId === 'all' && checkbox.checked) {
                    filterItems.forEach(otherItem => {
                        if (otherItem !== this) {
                            otherItem.classList.remove('active');
                            otherItem.querySelector('input[type="checkbox"]').checked = false;
                        }
                    });
                } else if (categoryId !== 'all' && checkbox.checked) {
                    // Uncheck "all" when others are selected
                    const allItem = document.querySelector('.filter-item[data-category="all"]');
                    allItem.classList.remove('active');
                    allItem.querySelector('input[type="checkbox"]').checked = false;
                }

                // Apply filter
                applyFilter();
            });
        });

        function applyFilter() {
            const activeFilters = [];
            filterItems.forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox.checked && item.dataset.category !== 'all') {
                    activeFilters.push(item.dataset.category);
                }
            });

            // Show/hide markers based on filter
            markers.forEach(({ marker, data }) => {
                if (activeFilters.length === 0 || activeFilters.includes(String(data.kategori_id))) {
                    marker.addTo(map);
                } else {
                    map.removeLayer(marker);
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            markers.forEach(({ marker, data }) => {
                const matchesSearch = data.nama_tempat.toLowerCase().includes(searchTerm) ||
                                    data.alamat.toLowerCase().includes(searchTerm) ||
                                    data.nama_kategori.toLowerCase().includes(searchTerm);

                if (matchesSearch) {
                    marker.addTo(map);

                    // If searching and found only one result, center on it
                    if (searchTerm && markers.filter(m =>
                        m.data.nama_tempat.toLowerCase().includes(searchTerm)).length === 1) {
                        map.setView([data.latitude, data.longitude], 16);
                        marker.openPopup();
                    }
                } else {
                    map.removeLayer(marker);
                }
            });
        });
    </script>
</body>
</html>
