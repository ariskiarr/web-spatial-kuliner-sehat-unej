@props(['id' => 'deleteModal'])

<!-- Modal Konfirmasi Hapus -->
<div id="{{ $id }}" style="display: none; backdrop-filter: blur(4px);" class="fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Hapus dari Favorit?</h3>
            <p class="text-sm text-gray-600 text-center mb-6">
                Apakah Anda yakin ingin menghapus <span id="tempatName" class="font-semibold text-gray-900"></span> dari daftar favorit?
            </p>
            <div class="flex gap-3">
                <button onclick="closeModal()" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                    Batal
                </button>
                <button onclick="confirmDelete()" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Close modal on backdrop click
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endpush
@endonce
