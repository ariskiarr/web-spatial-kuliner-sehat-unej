<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Menu - {{ $tempatMakan->nama_tempat }}</title>

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
            max-width: 1400px;
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
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .header h1 {
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .restaurant-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 10px;
        }

        .restaurant-info {
            color: #888;
            font-size: 14px;
            margin-top: 8px;
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

        .header-actions {
            display: flex;
            gap: 12px;
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

        .btn-primary {
            background: #10b981;
            color: white;
        }

        .btn-primary:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-back {
            background: #2d2d2d;
            color: white;
            border: 1px solid #444;
        }

        .btn-back:hover {
            background: #3a3a3a;
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

        .alert-success {
            background: #10b981;
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #2d2d2d;
            padding: 24px;
            border-radius: 15px;
            border-left: 4px solid;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.orange {
            border-color: #f59e0b;
        }

        .stat-card.green {
            border-color: #10b981;
        }

        .stat-card.blue {
            border-color: #3b82f6;
        }

        .stat-card.purple {
            border-color: #8b5cf6;
        }

        .stat-label {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
        }

        /* Table Container */
        .table-container {
            background: #2d2d2d;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .table-header h2 {
            font-size: 22px;
            font-weight: 600;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background: #000;
        }

        thead th {
            padding: 16px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #aaa;
        }

        tbody tr {
            border-bottom: 1px solid #2d2d2d;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #252525;
        }

        tbody td {
            padding: 18px 16px;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-kalori {
            background: #f59e0b;
            color: white;
        }

        .badge-kalori.low {
            background: #10b981;
        }

        .badge-kalori.high {
            background: #dc2626;
        }

        .badge-harga {
            background: #3b82f6;
            color: white;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 6px;
        }

        .btn-edit {
            background: #f59e0b;
            color: white;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        .btn-delete {
            background: #dc2626;
            color: white;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            color: #555;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #888;
            margin-bottom: 25px;
        }

        /* Menu Item */
        .menu-name {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .menu-meta {
            font-size: 12px;
            color: #888;
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
                        <span>📋</span>
                        Kelola Menu
                        <span class="restaurant-badge">{{ $tempatMakan->nama_tempat }}</span>
                    </h1>
                    <div class="restaurant-info">
                        📍 {{ $tempatMakan->alamat }}
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.tempat-makan.index') }}" class="btn btn-back">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('admin.menu-makan.create', $tempatMakan->id) }}" class="btn btn-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Tambah Menu
                    </a>
                </div>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a> /
                <a href="{{ route('admin.tempat-makan.index') }}">Tempat Makan</a> /
                Menu
            </div>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($menus->count() > 0)
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card orange">
                    <div class="stat-label">Total Menu</div>
                    <div class="stat-value">{{ $menus->count() }}</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-label">Rata-rata Harga</div>
                    <div class="stat-value" style="font-size: 20px;">Rp {{ number_format($menus->avg('harga'), 0, ',', '.') }}</div>
                </div>
                <div class="stat-card blue">
                    <div class="stat-label">Harga Tertinggi</div>
                    <div class="stat-value" style="font-size: 20px;">Rp {{ number_format($menus->max('harga'), 0, ',', '.') }}</div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-label">Rata-rata Kalori</div>
                    <div class="stat-value">{{ number_format($menus->avg('kalori'), 0) }} <span style="font-size: 16px;">kcal</span></div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <div class="table-header">
                    <h2>🍽️ Daftar Menu</h2>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">NO</th>
                            <th>NAMA MENU</th>
                            <th>HARGA</th>
                            <th>KALORI</th>
                            <th style="text-align: center; width: 200px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $index => $menu)
                            <tr>
                                <td style="font-weight: 600; color: #10b981;">
                                    #{{ $index + 1 }}
                                </td>
                                <td>
                                    <div class="menu-name">{{ $menu->nama_menu }}</div>
                                    @php
                                        $kaloriCategory = $menu->kalori < 200 ? 'Rendah' : ($menu->kalori < 400 ? 'Sedang' : 'Tinggi');
                                        $priceCategory = $menu->harga < 15000 ? 'Murah' : ($menu->harga < 50000 ? 'Sedang' : 'Mahal');
                                    @endphp
                                    <div class="menu-meta">
                                        Kategori: <strong>{{ $kaloriCategory }}</strong> kalori, <strong>{{ $priceCategory }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-harga">
                                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if($menu->kalori)
                                        @php
                                            $badgeClass = 'badge-kalori';
                                            if($menu->kalori < 200) $badgeClass .= ' low';
                                            elseif($menu->kalori > 500) $badgeClass .= ' high';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            🔥 {{ number_format($menu->kalori, 0) }} kcal
                                        </span>
                                    @else
                                        <span style="color: #888;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.menu-makan.edit', [$tempatMakan->id, $menu->id]) }}"
                                           class="btn btn-sm btn-edit">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('admin.menu-makan.destroy', [$tempatMakan->id, $menu->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus {{ $menu->nama_menu }}?');"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-delete">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="table-container">
                <div class="empty-state">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                    <h3>Belum Ada Menu</h3>
                    <p>Belum ada menu untuk tempat makan ini. Tambahkan menu pertama!</p>
                    <a href="{{ route('admin.menu-makan.create', $tempatMakan->id) }}" class="btn btn-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Tambah Menu Pertama
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
