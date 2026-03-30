@extends('layouts.app')

@section('page-title', 'Import Data Excel')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg mb-6">
        <p class="text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-6">
        <p class="text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-6">
        <ul class="list-disc list-inside text-red-800">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
        <div class="flex">
            <i class="fas fa-info-circle text-blue-500 text-2xl mr-4"></i>
            <div>
                <h3 class="font-semibold text-blue-900 mb-2">📋 Panduan Import Excel</h3>
                <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                    <li>File harus dalam format .xlsx atau .xls</li>
                    <li><strong>Sistem akan import dari tab "Data All"</strong></li>
                    <li>Semua rows yang hidden atau filtered akan otomatis di-unhide</li>
                    <li>Kolom pertama harus berisi header sesuai template</li>
                    <li>Pastikan NIM dan Name tidak kosong</li>
                    <li>Sistem akan otomatis create/update data berdasarkan NIM</li>
                    <li><strong>Status warna akan otomatis di-set berdasarkan "Tindak Lanjut SRSC"</strong></li>
                </ul>
                <div class="mt-4">
                    <a href="{{ route('download.template') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                        <i class="fas fa-download mr-2"></i>
                        Download Template Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Upload File Excel</h2>
        
        <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            
            <!-- File Input -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih File Excel <span class="text-red-500">*</span>
                </label>
                <input 
                    type="file" 
                    name="excel_file" 
                    id="excel_file"
                    accept=".xlsx,.xls"
                    required
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2.5"
                >
                <p class="mt-1 text-sm text-gray-500">XLSX atau XLS maksimal 10MB</p>
                
                <!-- Show selected file -->
                <p id="fileName" class="mt-2 text-sm text-green-600 font-medium"></p>
            </div>

            <!-- Options -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        name="replace_existing" 
                        id="replace_existing"
                        value="1"
                        class="w-4 h-4 mt-1 text-blue-600 border-gray-300 rounded"
                    >
                    <label for="replace_existing" class="ml-3">
                        <span class="font-medium text-gray-700">Replace All Data</span>
                        <p class="text-sm text-gray-500">⚠️ Hapus semua data lama dan ganti dengan data dari Excel</p>
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between pt-4 border-t">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                >
                    <i class="fas fa-upload mr-2"></i>
                    Import Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Show selected file name
document.getElementById('excel_file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || '';
    document.getElementById('fileName').textContent = fileName ? '📄 File: ' + fileName : '';
});

// Debug form submission
document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('excel_file');
    
    if (!fileInput.files || fileInput.files.length === 0) {
        e.preventDefault();
        alert('❌ Pilih file Excel dulu!');
        return false;
    }
    
    // Show loading
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
    
    console.log('Form submitting with file:', fileInput.files[0].name);
});
</script>
@endsection