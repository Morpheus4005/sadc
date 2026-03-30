@extends('layouts.app')

@section('page-title', 'Tambah Periode Akademik')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('periode.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Periode
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-plus-circle mr-2"></i>
            Tambah Periode Akademik Baru
        </h2>

        <form action="{{ route('periode.store') }}" method="POST">
            @csrf

            <!-- Semester -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Semester <span class="text-red-500">*</span>
                </label>
                <select 
                    name="semester" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Pilih Semester</option>
                    <option value="Ganjil">Ganjil (Semester 1)</option>
                    <option value="Genap">Genap (Semester 2)</option>
                </select>
                @error('semester')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tahun Ajaran -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="tahun_ajaran" 
                    value="{{ old('tahun_ajaran') }}"
                    placeholder="2025/2026"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                <p class="text-xs text-gray-500 mt-1">Format: YYYY/YYYY (contoh: 2025/2026)</p>
                @error('tahun_ajaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Keterangan (Opsional)
                </label>
                <textarea 
                    name="keterangan" 
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Catatan tambahan tentang periode ini..."
                >{{ old('keterangan') }}</textarea>
            </div>

            <!-- Preview -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Preview nama periode:</strong>
                    <span id="preview-text" class="font-mono">Pilih semester dan tahun ajaran...</span>
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a 
                    href="{{ route('periode.index') }}" 
                    class="flex-1 px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-center transition"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    <i class="fas fa-save mr-2"></i>
                    Simpan Periode
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Live preview
document.addEventListener('DOMContentLoaded', function() {
    const semesterSelect = document.querySelector('select[name="semester"]');
    const tahunInput = document.querySelector('input[name="tahun_ajaran"]');
    const previewText = document.getElementById('preview-text');
    
    function updatePreview() {
        const semester = semesterSelect.value;
        const tahun = tahunInput.value;
        
        if (semester && tahun) {
            previewText.textContent = `${semester} ${tahun}`;
        } else {
            previewText.textContent = 'Pilih semester dan tahun ajaran...';
        }
    }
    
    semesterSelect.addEventListener('change', updatePreview);
    tahunInput.addEventListener('input', updatePreview);
});
</script>
@endsection