<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Menu - {{ $tempatMakan->nama_tempat }}</title>

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
            max-width: 900px;
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

        .header h1 {
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .restaurant-badge {
            display: inline-block;
            background: #f59e0b;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 10px;
        }

        .menu-badge {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            margin-left: 10px;
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

        /* Form Container */
        .form-container {
            background: #2d2d2d;
            border-radius: 15px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease-out;
        }

        .alert-danger {
            background: #dc2626;
            color: white;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Sections */
        .section {
            margin-bottom: 35px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #f59e0b;
            color: #f59e0b;
        }

        .section-kalori .section-title {
            border-bottom-color: #f59e0b;
            color: #f59e0b;
        }

        /* Form Groups */
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

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background: #1a1a1a;
            border: 2px solid #444;
            border-radius: 10px;
            color: white;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .form-group input::placeholder {
            color: #666;
        }

        .form-help {
            font-size: 12px;
            color: #888;
            margin-top: 6px;
        }

        /* Kalori Section */
        .section-kalori {
            background: #1a1a1a;
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #f59e0b;
        }

        .current-kalori-box {
            background: #2d2d2d;
            padding: 16px;
            border-radius: 10px;
            border-left: 4px solid #10b981;
            margin-bottom: 20px;
        }

        .current-kalori-box strong {
            color: #10b981;
            font-size: 24px;
        }

        .kalori-input-section .form-group input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        /* Buttons */
        .btn {
            padding: 14px 28px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            font-size: 14px;
        }

        .btn-auto-kalori {
            background: #f59e0b;
            color: white;
            width: 100%;
            margin-bottom: 15px;
        }

        .btn-auto-kalori:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        .btn-auto-kalori:disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
        }

        .estimated-result {
            background: #2d2d2d;
            padding: 16px;
            border-radius: 10px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 20px;
            display: none;
        }

        .estimated-result.show {
            display: block;
            animation: slideIn 0.3s ease-out;
        }

        .estimated-result strong {
            color: #f59e0b;
            font-size: 20px;
        }

        /* Action Buttons */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #444;
        }

        .btn-cancel {
            background: #2d2d2d;
            color: white;
            border: 2px solid #444;
        }

        .btn-cancel:hover {
            background: #3a3a3a;
            border-color: #555;
        }

        .btn-submit {
            background: #f59e0b;
            color: white;
        }

        .btn-submit:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        /* Info Box */
        .info-box {
            background: #1a1a1a;
            padding: 16px 20px;
            border-radius: 10px;
            border-left: 4px solid #3b82f6;
            margin-bottom: 25px;
        }

        .info-box-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #3b82f6;
            margin-bottom: 8px;
        }

        .info-box ul {
            margin-left: 20px;
            color: #aaa;
            font-size: 13px;
        }

        .info-box li {
            margin-bottom: 6px;
        }

        /* Loading Animation */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <span>✏️</span>
                Edit Menu
                <span class="restaurant-badge">{{ $tempatMakan->nama_tempat }}</span>
                <span class="menu-badge">{{ $menu->nama_menu }}</span>
            </h1>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a> /
                <a href="{{ route('admin.tempat-makan.index') }}">Tempat Makan</a> /
                <a href="{{ route('admin.menu-makan.index', $tempatMakan->id) }}">Menu</a> /
                Edit
            </div>
        </div>

        <!-- Alert -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <strong>Whoops!</strong> Ada masalah dengan input Anda:
                    <ul style="margin-top: 8px; margin-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Form Container -->
        <div class="form-container">
            <!-- Info Box -->
            <div class="info-box">
                <div class="info-box-title">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Panduan Pengisian
                </div>
                <ul>
                    <li>Ubah informasi menu sesuai kebutuhan</li>
                    <li>Gunakan fitur hitung ulang untuk update kalori otomatis</li>
                    <li>Pastikan harga dan kalori sudah sesuai</li>
                    <li>Klik "Update Menu" untuk menyimpan perubahan</li>
                </ul>
            </div>

            <form action="{{ route('admin.menu-makan.update', [$tempatMakan->id, $menu->id]) }}" method="POST" id="menuForm">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="section">
                    <div class="section-title">
                        <span>📝</span>
                        Informasi Dasar
                    </div>

                    <div class="form-group">
                        <label for="nama_menu">
                            Nama Menu <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="nama_menu"
                               id="nama_menu"
                               value="{{ old('nama_menu', $menu->nama_menu) }}"
                               placeholder="Contoh: Nasi Goreng Ayam"
                               required>
                        <div class="form-help">Masukkan nama menu yang jelas dan spesifik</div>
                    </div>

                    <div class="form-group">
                        <label for="harga">
                            Harga (Rp) <span class="required">*</span>
                        </label>
                        <input type="number"
                               name="harga"
                               id="harga"
                               value="{{ old('harga', $menu->harga) }}"
                               placeholder="Contoh: 25000"
                               min="0"
                               step="1000"
                               required>
                        <div class="form-help">Masukkan harga dalam rupiah (tanpa desimal)</div>
                    </div>
                </div>

                <!-- Kalori Section -->
                <div class="section section-kalori">
                    <div class="section-title">
                        <span>🔥</span>
                        Informasi Kalori
                    </div>

                    <!-- Current Kalori Display -->
                    @if($menu->kalori)
                        <div class="current-kalori-box">
                            <div style="font-size: 13px; color: #aaa; margin-bottom: 6px;">
                                📊 Kalori Saat Ini:
                            </div>
                            <strong>{{ number_format($menu->kalori, 0) }}</strong> kcal
                        </div>
                    @endif

                    <!-- Auto Calculate Button -->
                    <button type="button" id="btnAutoKalori" class="btn btn-auto-kalori">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        </svg>
                        Hitung Ulang Kalori Otomatis
                    </button>
                    <div class="form-help" style="text-align: center; margin-bottom: 20px;">
                        Sistem akan mengestimasi kalori berdasarkan nama menu terbaru menggunakan AI
                    </div>

                    <!-- Estimated Result Display -->
                    <div id="estimatedResult" class="estimated-result">
                        <div style="font-size: 13px; color: #ccc; margin-bottom: 6px;">
                            ✨ Estimasi Kalori Baru:
                        </div>
                        <strong id="estimatedKaloriValue">0</strong> kcal
                    </div>

                    <!-- Kalori Input -->
                    <div class="kalori-input-section">
                        <div class="form-group">
                            <label for="kalori">
                                Kalori (kcal)
                            </label>
                            <input type="number"
                                   name="kalori"
                                   id="kalori"
                                   value="{{ old('kalori', $menu->kalori) }}"
                                   placeholder="Masukkan manual atau klik tombol di atas"
                                   min="0"
                                   step="1">
                            <input type="hidden" name="auto_kalori" id="auto_kalori" value="0">
                            <div class="form-help">Opsional: Bisa diubah manual atau menggunakan estimasi otomatis</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <a href="{{ route('admin.menu-makan.index', $tempatMakan->id) }}" class="btn btn-cancel">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                        </svg>
                        Update Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('btnAutoKalori').addEventListener('click', function() {
            const namaMenu = document.getElementById('nama_menu').value;

            if (!namaMenu) {
                alert('⚠️ Silakan isi nama menu terlebih dahulu!');
                document.getElementById('nama_menu').focus();
                return;
            }

            // Show loading state
            const btn = this;
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Menghitung ulang kalori...';

            // Call API to estimate calories
            fetch('{{ route("admin.menu.estimate-kalori") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ nama_menu: namaMenu })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update kalori input
                    document.getElementById('kalori').value = data.kalori;
                    document.getElementById('auto_kalori').value = '1';

                    // Show estimated result
                    document.getElementById('estimatedKaloriValue').textContent = data.kalori;
                    document.getElementById('estimatedResult').classList.add('show');

                    // Success notification
                    alert('✅ Kalori berhasil dihitung ulang: ' + data.kalori + ' kcal\n\nAnda bisa mengubahnya secara manual jika diperlukan.');
                } else {
                    alert('❌ Gagal menghitung kalori. Silakan input manual.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan saat menghitung kalori. Silakan coba lagi atau input manual.');
            })
            .finally(() => {
                // Restore button state
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            });
        });

        // Optional: Clear estimated result when nama_menu changes
        document.getElementById('nama_menu').addEventListener('input', function() {
            document.getElementById('estimatedResult').classList.remove('show');
        });
    </script>
</body>
</html>
