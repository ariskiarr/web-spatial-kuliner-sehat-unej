<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Tempat Makan - CalorieMaps</title>

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
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            color: white;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        /* Header */
        .header {
            background: #000;
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        .header-top {
            display: flex;
            justify-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .header h1 {
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .breadcrumb {
            color: #888;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            font-size: 14px;
        }

        .btn-back {
            background: #2d2d2d;
            color: white;
            border: 1px solid #444;
        }

        .btn-back:hover {
            background: #3a3a3a;
        }

        /* Form Container */
        .form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .form-section {
            background: #2d2d2d;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title.orange {
            border-color: #f59e0b;
        }

        .section-title.blue {
            border-color: #3b82f6;
        }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            background: #dc2626;
            color: white;
        }

        .alert ul {
            margin-top: 10px;
            margin-left: 20px;
        }

        .alert li {
            margin-bottom: 5px;
        }

        /* Info Box */
        .info-box {
            background: rgba(245, 158, 11, 0.1);
            border-left: 4px solid #f59e0b;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box p {
            font-size: 14px;
            color: #f59e0b;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #ccc;
        }

        .required {
            color: #dc2626;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: #1a1a1a;
            border: 2px solid #444;
            border-radius: 10px;
            color: white;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #f59e0b;
            background: #222;
        }

        .form-control::placeholder {
            color: #666;
        }

        .form-control:read-only {
            background: #2d2d2d;
            cursor: not-allowed;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        select.form-control {
            cursor: pointer;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        /* Map */
        #map {
            width: 100%;
            height: 500px;
            border-radius: 12px;
            border: 2px solid #444;
        }

        /* Action Buttons */
        .form-actions {
            background: #2d2d2d;
            padding: 25px 30px;
            border-radius: 15px;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        .btn-cancel {
            background: #6b7280;
            color: white;
        }

        .btn-cancel:hover {
            background: #4b5563;
        }

        .btn-submit {
            background: #f59e0b;
            color: white;
            padding: 14px 32px;
            font-size: 16px;
        }

        .btn-submit:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }

        /* Current Data Badge */
        .current-badge {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .form-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div>
                    <h1>
                        <span>✏️</span>
                        Edit Tempat Makan
                        <span class="current-badge">{{ $tempatMakan->nama_tempat }}</span>
                    </h1>
                </div>
                <a href="{{ route('admin.tempat-makan.index') }}" class="btn btn-back">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Kembali
                </a>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a> /
                <a href="{{ route('admin.tempat-makan.index') }}">Tempat Makan</a> /
                Edit
            </div>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="alert">
                <strong>⚠️ Ada kesalahan!</strong> Periksa kembali input Anda:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.tempat-makan.update', $tempatMakan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Form Container -->
            <div class="form-container">
                <!-- Left Section: Form Inputs -->
                <div class="form-section">
                    <h2 class="section-title blue">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                        </svg>
                        Informasi Tempat Makan
                    </h2>

                    <div class="form-group">
                        <label for="nama_tempat">
                            Nama Tempat <span class="required">*</span>
                        </label>
                        <input type="text"
                            name="nama_tempat"
                            id="nama_tempat"
                            value="{{ old('nama_tempat', $tempatMakan->nama_tempat) }}"
                            placeholder="Contoh: Warung Makan Sederhana"
                            class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="kategori_id">
                            Kategori <span class="required">*</span>
                        </label>
                        <select name="kategori_id" id="kategori_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ old('kategori_id', $tempatMakan->kategori_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="alamat">
                            Alamat Lengkap <span class="required">*</span>
                        </label>
                        <textarea name="alamat"
                            id="alamat"
                            placeholder="Masukkan alamat lengkap tempat makan..."
                            class="form-control"
                            required>{{ old('alamat', $tempatMakan->alamat) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="jam_operasional">
                            Jam Operasional
                        </label>
                        <input type="text"
                            name="jam_operasional"
                            id="jam_operasional"
                            value="{{ old('jam_operasional', $tempatMakan->jam_operasional) }}"
                            placeholder="Contoh: 08:00 - 22:00"
                            class="form-control">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="latitude">
                                Latitude <span class="required">*</span>
                            </label>
                            <input type="text"
                                name="latitude"
                                id="latitude"
                                value="{{ old('latitude', $tempatMakan->latitude) }}"
                                class="form-control"
                                readonly
                                required>
                        </div>
                        <div class="form-group">
                            <label for="longitude">
                                Longitude <span class="required">*</span>
                            </label>
                            <input type="text"
                                name="longitude"
                                id="longitude"
                                value="{{ old('longitude', $tempatMakan->longitude) }}"
                                class="form-control"
                                readonly
                                required>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Map -->
                <div class="form-section">
                    <h2 class="section-title orange">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Ubah Lokasi di Peta
                    </h2>

                    <div class="info-box">
                        <p>
                            <strong>📍 Cara Menggunakan:</strong><br>
                            Marker biru menunjukkan lokasi saat ini. Klik pada peta untuk mengubah lokasi.
                        </p>
                    </div>

                    <div id="map"></div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <a href="{{ route('admin.tempat-makan.index') }}" class="btn btn-cancel">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="btn btn-submit">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                    </svg>
                    Update Data
                </button>
            </div>
        </form>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <script>
        // Get existing coordinates
        const existingLat = {{ $tempatMakan->latitude ?? 'null' }};
        const existingLng = {{ $tempatMakan->longitude ?? 'null' }};

        let initialLat = existingLat !== null ? existingLat : -8.1706;
        let initialLng = existingLng !== null ? existingLng : 113.7026;
        let initialZoom = existingLat !== null ? 15 : 13;

        const map = L.map('map').setView([initialLat, initialLng], initialZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        let marker = null;

        // Place initial marker
        if (existingLat !== null && existingLng !== null) {
            marker = L.marker([existingLat, existingLng]).addTo(map);
            marker.bindPopup(`<b>Lokasi Saat Ini</b><br>Lat: ${existingLat.toFixed(6)}<br>Lng: ${existingLng.toFixed(6)}`).openPopup();
        }

        // Map click handler
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(`<b>Lokasi Baru</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
        });

        // Handle old values from validation
        @if(old('latitude') && old('longitude'))
            const oldLat = {{ old('latitude') }};
            const oldLng = {{ old('longitude') }};

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([oldLat, oldLng]).addTo(map);
            marker.bindPopup(`<b>Lokasi Dipilih</b><br>Lat: ${oldLat.toFixed(6)}<br>Lng: ${oldLng.toFixed(6)}`);
            map.setView([oldLat, oldLng], 15);
        @endif
    </script>
</body>
</html>
