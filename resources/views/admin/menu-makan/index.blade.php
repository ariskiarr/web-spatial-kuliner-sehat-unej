<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Kelola Menu - {{ $tempatMakan->nama_tempat }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $tempatMakan->alamat }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.menu-makan.create', $tempatMakan->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    + Tambah Menu
                </a>
                <a href="{{ route('admin.tempat-makan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($menus->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto border-collapse border border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">No</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Menu</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Harga</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Kalori</th>
                                        <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($menus as $index => $menu)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">
                                                <strong>{{ $menu->nama_menu }}</strong>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">
                                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">
                                                @if($menu->kalori)
                                                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded font-semibold">
                                                        {{ number_format($menu->kalori, 0) }} kcal
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <div class="flex justify-center gap-2">
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('admin.menu-makan.edit', [$tempatMakan->id, $menu->id]) }}" 
                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-sm">
                                                        Edit
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <form action="{{ route('admin.menu-makan.destroy', [$tempatMakan->id, $menu->id]) }}" 
                                                        method="POST" 
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?');"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="mt-4 bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Total Menu:</strong> {{ $menus->count() }} item
                            </p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg mb-2">Belum ada menu untuk tempat makan ini.</p>
                            <p class="text-gray-400 text-sm mb-4">Tambahkan menu pertama untuk memulai!</p>
                            <a href="{{ route('admin.menu-makan.create', $tempatMakan->id) }}" 
                                class="inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                + Tambah Menu Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
