@extends('layouts.app')

@section('page-title', 'Tambah Data Mahasiswa')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Admit Term -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Admit Term <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="admit_term" 
                        value="{{ old('admit_term') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g., 2410"
                        required
                    >
                    @error('admit_term')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campus -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Campus <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="campus" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="">Pilih Campus</option>
                        <option value="Binus Bandung" {{ old('campus') == 'Binus Bandung' ? 'selected' : '' }}>Binus Bandung</option>
                        <option value="Binus Jakarta" {{ old('campus') == 'Binus Jakarta' ? 'selected' : '' }}>Binus Jakarta</option>
                        <option value="Binus Bekasi" {{ old('campus') == 'Binus Bekasi' ? 'selected' : '' }}>Binus Bekasi</option>
                    </select>
                    @error('campus')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Faculty/School -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Faculty/School <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="faculty" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="">Pilih Faculty</option>
                        <option value="School of Design" {{ old('faculty') == 'School of Design' ? 'selected' : '' }}>School of Design</option>
                        <option value="School of Computer Science" {{ old('faculty') == 'School of Computer Science' ? 'selected' : '' }}>School of Computer Science</option>
                        <option value="BINUS Business School" {{ old('faculty') == 'BINUS Business School' ? 'selected' : '' }}>BINUS Business School</option>
                    </select>
                    @error('faculty')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Program -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Program <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="program" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="">Pilih Program</option>
                        <option value="Computer Science" {{ old('program') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                        <option value="Visual Communication Design" {{ old('program') == 'Visual Communication Design' ? 'selected' : '' }}>Visual Communication Design</option>
                        <option value="Interior Design" {{ old('program') == 'Interior Design' ? 'selected' : '' }}>Interior Design</option>
                        <option value="Creativepreneurship" {{ old('program') == 'Creativepreneurship' ? 'selected' : '' }}>Creativepreneurship</option>
                        <option value="Digital Business Innovation" {{ old('program') == 'Digital Business Innovation' ? 'selected' : '' }}>Digital Business Innovation</option>
                        <option value="Interactive Design and Technology" {{ old('program') == 'Interactive Design and Technology' ? 'selected' : '' }}>Interactive Design and Technology</option>
                    </select>
                    @error('program')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Binusian ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Binusian ID <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="binusian_id" 
                        value="{{ old('binusian_id') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g., BN125391570"
                        required
                    >
                    @error('binusian_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIM -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        NIM <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nim" 
                        value="{{ old('nim') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g., 2802468246"
                        required
                    >
                    @error('nim')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Binusian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Binusian <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="binusian" 
                        value="{{ old('binusian') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g., 28"
                        required
                    >
                    @error('binusian')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Mahasiswa <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Nama lengkap"
                        required
                    >
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Tindak Lanjut -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Tindak Lanjut SRSC <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="tindak_lanjut" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="">Pilih Status</option>
                        <option value="Merespon">Merespon</option>
                        <option value="Mengajukan Undur Diri/DO">Mengajukan Undur Diri/DO</option>
                        <option value="Mengajukan Pindah Jurusan">Mengajukan Pindah Jurusan</option>
                        <option value="Data Pengajuan Reactive 25.2">Data Pengajuan Reactive 25.2</option>
                        <option value="Sudah Terdata Aktif 25.2">Sudah Terdata Aktif 25.2</option>
                        <option value="Terhubung Tapi Tidak Merespon">Terhubung Tapi Tidak Merespon</option>
                        <option value="Belum Terhubung">Belum Terhubung</option>
                        <option value="Tidak Realistis 7 Tahun">Tidak Realistis 7 Tahun</option>
                        <option value="Dalam Proses Reactive">Dalam Proses Reactive</option>
                        <option value="Unofficial Leave">Unofficial Leave</option>
                    </select>
                    @error('tindak_lanjut')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Evaluasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Evaluasi <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="evaluasi" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="">Pilih Evaluasi</option>
                        <option value="Reguler">Reguler</option>
                        <option value="Non Reguler">Non Reguler</option>
                    </select>
                    @error('evaluasi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SKS Kumulatif -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        SKS Kumulatif
                    </label>
                    <input 
                        type="number" 
                        name="sks_kumulatif" 
                        value="{{ old('sks_kumulatif', 0) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- SKS Sisa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        SKS Sisa
                    </label>
                    <input 
                        type="number" 
                        name="sks_sisa" 
                        value="{{ old('sks_sisa', 146) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Study Target 10 SMT -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Study Target 10 SMT
                    </label>
                    <input 
                        type="text" 
                        name="study_target_10" 
                        value="{{ old('study_target_10') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Study Target 14 SMT
                    </label>
                    <input 
                        type="text" 
                        name="study_target_14" 
                        value="{{ old('study_target_14') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                </div>

            </div>

            <!-- Prediksi SMT Selesai -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Prediksi SMT Selesai
                </label>
                <input 
                    type="text" 
                    name="prediksi_smt_selesai" 
                    value="{{ old('prediksi_smt_selesai') }}"
                    class="w-full px-4 py-2 border rounded-lg"
                    placeholder="e.g., SMT 12"
                >
            </div>

            <!-- No HP -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    No. HP Mahasiswa
                </label>
                <input 
                    type="text" 
                    name="no_hp" 
                    value="{{ old('no_hp') }}"
                    class="w-full px-4 py-2 border rounded-lg"
                    placeholder="e.g., 6281234567890"
                >
            </div>

            <!-- Notes SRSC -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Notes SRSC
                </label>
                <textarea 
                    name="notes_srsc" 
                    rows="3"
                    class="w-full px-4 py-2 border rounded-lg"
                    placeholder="Catatan dari SRSC..."
                >{{ old('notes_srsc') }}</textarea>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                <a href="{{ route('students.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection