<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Tempat Makan - CalorieMaps</title>

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

        .section-title.green {
            border-color: #10b981;
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
            background: rgba(16, 185, 129, 0.1);
            border-left: 4px solid #10b981;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box p {
            font-size: 14px;
            color: #10b981;
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
            border-color: #3b82f6;
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
            background: #10b981;
            color: white;
            padding: 14px 32px;
            font-size: 16px;
        }

        .btn-submit:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
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
                        <span>➕</span>
                        Tambah Tempat Makan Baru
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
                Tambah Baru
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

        <form action="{{ route('admin.tempat-makan.store') }}" method="POST">
            @csrf

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
                            value="{{ old('nama_tempat') }}"
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
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
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
                            required>{{ old('alamat') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="jam_operasional">
                            Jam Operasional
                        </label>
                        <input type="text"
                            name="jam_operasional"
                            id="jam_operasional"
                            value="{{ old('jam_operasional') }}"
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
                                value="{{ old('latitude', $latitude ?? '') }}"
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
                                value="{{ old('longitude', $longitude ?? '') }}"
                                class="form-control"
                                readonly
                                required>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Map -->
                <div class="form-section">
                    <h2 class="section-title green">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Pilih Lokasi di Peta
                    </h2>

                    <div class="info-box">
                        <p>
                            <strong>📍 Cara Menggunakan:</strong><br>
                            Klik pada peta untuk menandai lokasi tempat makan. Koordinat akan otomatis terisi.
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
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <script>
        // Initialize map
        const map = L.map('map').setView([-8.1706, 113.7026], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        let marker = null;

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
            marker.bindPopup(`<b>Lokasi Dipilih</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
        });

        // If coordinates exist from URL
        @if((isset($latitude) && isset($longitude)) || (old('latitude') && old('longitude')))
            @php
                $lat = old('latitude', $latitude ?? null);
                $lng = old('longitude', $longitude ?? null);
            @endphp

            @if($lat && $lng)
                const initialLat = {{ $lat }};
                const initialLng = {{ $lng }};

                marker = L.marker([initialLat, initialLng]).addTo(map);
                marker.bindPopup(`<b>Lokasi Dipilih</b><br>Lat: ${initialLat.toFixed(6)}<br>Lng: ${initialLng.toFixed(6)}`).openPopup();
                map.setView([initialLat, initialLng], 16);
            @endif
        @endif
    </script>
</body>
</html>
