<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Menu - {{ $tempatMakan->nama_tempat }}
                </h2>
            </div>
            <a href="{{ route('admin.menu-makan.index', $tempatMakan->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <strong>Whoops!</strong> Ada masalah dengan input Anda:<br><br>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.menu-makan.update', [$tempatMakan->id, $menu->id]) }}" method="POST" id="menuForm">
                        @csrf
                        @method('PUT')

                        <!-- Nama Menu -->
                        <div class="mb-4">
                            <label for="nama_menu" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Menu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                name="nama_menu" 
                                id="nama_menu" 
                                value="{{ old('nama_menu', $menu->nama_menu) }}"
                                placeholder="Contoh: Nasi Goreng Ayam"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                        </div>

                        <!-- Harga -->
                        <div class="mb-4">
                            <label for="harga" class="block text-sm font-semibold text-gray-700 mb-2">
                                Harga (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                name="harga" 
                                id="harga" 
                                value="{{ old('harga', $menu->harga) }}"
                                placeholder="Contoh: 25000"
                                min="0"
                                step="0.01"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                        </div>

                        <!-- Kalori Section -->
                        <div class="mb-4 bg-orange-50 border-2 border-orange-200 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Kalori (kcal)
                            </label>

                            <!-- Current Kalori Display -->
                            @if($menu->kalori)
                                <div class="mb-3 bg-white p-3 rounded-lg border border-green-300">
                                    <p class="text-sm text-gray-700">
                                        <strong>Kalori Saat Ini:</strong> <span class="text-green-600 font-bold">{{ number_format($menu->kalori, 0) }}</span> kcal
                                    </p>
                                </div>
                            @endif

                            <!-- Auto Calculate Button -->
                            <div class="mb-3">
                                <button type="button" 
                                    id="btnAutoKalori"
                                    class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Hitung Ulang Kalori Otomatis
                                </button>
                                <p class="text-xs text-gray-600 mt-2">Sistem akan mengestimasi kalori berdasarkan nama menu</p>
                            </div>

                            <!-- Kalori Input -->
                            <div>
                                <input type="number" 
                                    name="kalori" 
                                    id="kalori" 
                                    value="{{ old('kalori', $menu->kalori) }}"
                                    placeholder="Masukkan kalori manual atau klik tombol di atas"
                                    min="0"
                                    step="0.01"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                <input type="hidden" name="auto_kalori" id="auto_kalori" value="0">
                            </div>

                            <!-- Estimated Kalori Display -->
                            <div id="estimatedKaloriDisplay" class="mt-3 hidden">
                                <div class="bg-white p-3 rounded-lg border border-orange-300">
                                    <p class="text-sm text-gray-700">
                                        <strong>Estimasi Kalori Baru:</strong> <span id="estimatedKaloriValue" class="text-orange-600 font-bold"></span> kcal
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between mt-8 pt-6 border-t-2 border-gray-200">
                            <a href="{{ route('admin.menu-makan.index', $tempatMakan->id) }}" 
                                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                                ← Batal
                            </a>
                            <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg transition duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Update Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btnAutoKalori').addEventListener('click', function() {
            const namaMenu = document.getElementById('nama_menu').value;
            
            if (!namaMenu) {
                alert('Silakan isi nama menu terlebih dahulu!');
                return;
            }

            // Show loading state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menghitung...';

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
                    
                    // Show estimated kalori display
                    document.getElementById('estimatedKaloriValue').textContent = data.kalori;
                    document.getElementById('estimatedKaloriDisplay').classList.remove('hidden');
                    
                    // Success notification
                    alert('Kalori berhasil dihitung: ' + data.kalori + ' kcal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghitung kalori. Silakan coba lagi.');
            })
            .finally(() => {
                // Restore button state
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    </script>
</x-app-layout>
