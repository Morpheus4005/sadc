@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Import Data Mahasiswa</h1>
            <p class="text-gray-600 mt-2">Upload file Excel untuk mengimport data mahasiswa</p>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Error Alert -->
        @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Upload Form -->
                <form method="POST" action="{{ route('import.process') }}" enctype="multipart/form-data" id="importForm">
                    @csrf
                    
                    <!-- File Input -->
                    <div class="mb-6">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            File Excel <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Pilih file</span>
                                        <input 
                                            id="file" 
                                            name="file" 
                                            type="file" 
                                            class="sr-only" 
                                            accept=".xlsx,.xls"
                                            required
                                            onchange="document.getElementById('fileName').textContent = this.files[0] ? this.files[0].name : 'Tidak ada file dipilih'"
                                        >
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">XLSX, XLS (maksimal 10MB)</p>
                                <p id="fileName" class="text-sm text-blue-600 font-medium mt-2">Tidak ada file dipilih</p>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Format Info -->
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-blue-900 mb-2">📋 Format yang Didukung</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li class="flex items-start">
                                <span class="text-green-600 mr-2">✓</span>
                                <span><strong>Format LAMA:</strong> Sheet "Data All" dengan 18 kolom</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-600 mr-2">✓</span>
                                <span><strong>Format BARU:</strong> Sheet "Sheet1" dengan 14 kolom</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2">ℹ</span>
                                <span>Sistem akan mendeteksi format secara otomatis</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Current Periode -->
                    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">
                                <strong>Periode Aktif:</strong> 
                                <span class="text-blue-600 font-semibold">{{ \App\Helpers\PeriodeHelper::getCurrentPeriode() }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                            ← Kembali
                        </a>
                        <button 
                            type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">💡 Panduan Import</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                <li>Pastikan file Excel dalam format .xlsx atau .xls</li>
                <li>Untuk format lama, pastikan ada sheet bernama "Data All"</li>
                <li>Untuk format baru, pastikan ada sheet "Sheet1" dengan 14 kolom</li>
                <li>Ukuran file maksimal 10MB</li>
                <li>Data akan diimport ke periode: <strong class="text-blue-600">{{ \App\Helpers\PeriodeHelper::getCurrentPeriode() }}</strong></li>
            </ol>
        </div>
    </div>
</div>
@endsection