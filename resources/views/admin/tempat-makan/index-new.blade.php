<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Tempat Makan - CalorieMaps</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
            font-size: 28px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-badge {
            background: #3b82f6;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .breadcrumb {
            color: #888;
            font-size: 14px;
            margin-top: 5px;
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
            background: white;
            color: #1a1a1a;
        }

        .btn-primary:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.3);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

        .stat-card.blue {
            border-color: #3b82f6;
        }

        .stat-card.green {
            border-color: #10b981;
        }

        .stat-card.purple {
            border-color: #8b5cf6;
        }

        .stat-label {
            font-size: 13px;
            color: #aaa;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            font-size: 36px;
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

        .search-box {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-input {
            padding: 10px 16px;
            background: #1a1a1a;
            border: 1px solid #444;
            border-radius: 8px;
            color: white;
            width: 300px;
            font-size: 14px;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .search-input::placeholder {
            color: #888;
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
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-kategori {
            background: #3b82f6;
            color: white;
        }

        .badge-jam {
            background: #10b981;
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

        .btn-menu {
            background: #10b981;
            color: white;
        }

        .btn-menu:hover {
            background: #059669;
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

        /* Pagination */
        .pagination {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .pagination a,
        .pagination span {
            padding: 10px 16px;
            background: #1a1a1a;
            border: 1px solid #444;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        .pagination .active {
            background: white;
            color: #1a1a1a;
            border-color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1>
                    <span>🍴</span>
                    Kelola Tempat Makan
                </h1>
                <div class="breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a> / Tempat Makan
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-back">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.tempat-makan.create') }}" class="btn btn-primary">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Tambah Tempat Makan
                </a>
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

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-label">Total Tempat Makan</div>
                <div class="stat-value">{{ $tempatMakan->total() }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Halaman Saat Ini</div>
                <div class="stat-value">{{ $tempatMakan->currentPage() }} / {{ $tempatMakan->lastPage() }}</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-label">Data Per Halaman</div>
                <div class="stat-value">{{ $tempatMakan->perPage() }}</div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-header">
                <h2>📋 Daftar Tempat Makan</h2>
                <div class="search-box">
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari tempat makan...">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="color: #888;">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            @if($tempatMakan->count() > 0)
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA TEMPAT</th>
                            <th>KATEGORI</th>
                            <th>ALAMAT</th>
                            <th>JAM OPERASIONAL</th>
                            <th style="text-align: center;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tempatMakan as $index => $tempat)
                            <tr>
                                <td style="font-weight: 600; color: #3b82f6;">
                                    #{{ $tempatMakan->firstItem() + $index }}
                                </td>
                                <td>
                                    <div style="font-weight: 600; margin-bottom: 4px;">
                                        {{ $tempat->nama_tempat }}
                                    </div>
                                    <div style="font-size: 12px; color: #888;">
                                        📍 {{ number_format($tempat->latitude, 6) }}, {{ number_format($tempat->longitude, 6) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-kategori">
                                        {{ $tempat->nama_kategori }}
                                    </span>
                                </td>
                                <td style="max-width: 250px;">
                                    {{ $tempat->alamat }}
                                </td>
                                <td>
                                    @if($tempat->jam_operasional)
                                        <span class="badge badge-jam">
                                            ⏰ {{ $tempat->jam_operasional }}
                                        </span>
                                    @else
                                        <span style="color: #888;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.tempat-makan.edit', $tempat->id) }}"
                                           class="btn btn-sm btn-edit">
                                            ✏️ Edit
                                        </a>
                                        <a href="{{ route('admin.menu-makan.index', $tempat->id) }}"
                                           class="btn btn-sm btn-menu">
                                            📋 Menu
                                        </a>
                                        <form action="{{ route('admin.tempat-makan.destroy', $tempat->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus {{ $tempat->nama_tempat }}?');"
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

                <!-- Pagination -->
                <div class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($tempatMakan->onFirstPage())
                        <span>«</span>
                    @else
                        <a href="{{ $tempatMakan->previousPageUrl() }}">«</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($tempatMakan->getUrlRange(1, $tempatMakan->lastPage()) as $page => $url)
                        @if ($page == $tempatMakan->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($tempatMakan->hasMorePages())
                        <a href="{{ $tempatMakan->nextPageUrl() }}">»</a>
                    @else
                        <span>»</span>
                    @endif
                </div>
            @else
                <div class="empty-state">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h12a1 1 0 011 1v8a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm3 5a1 1 0 100-2 1 1 0 000 2zm5-1a1 1 0 11-2 0 1 1 0 012 0zm2 1a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <h3>Belum Ada Data</h3>
                    <p>Belum ada tempat makan yang terdaftar. Tambahkan tempat makan pertama Anda!</p>
                    <a href="{{ route('admin.tempat-makan.create') }}" class="btn btn-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Tambah Tempat Makan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('dataTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let row of rows) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            }
        });
    </script>
</body>
</html>
