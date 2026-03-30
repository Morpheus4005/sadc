@extends('layouts.app')

@section('page-title', 'Edit Data Mahasiswa')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Detail
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Data Mahasiswa</h2>

        <form action="{{ route('students.update', $student) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Admit Term -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Admit Term <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="admit_term" 
                        value="{{ old('admit_term', $student->admit_term) }}"
                        class="w-full px-4 py-2 border rounded-lg @error('admit_term') border-red-500 @enderror"
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
                    <select name="campus" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">Pilih Campus</option>
                        <option value="Binus Bandung" {{ old('campus', $student->campus) == 'Binus Bandung' ? 'selected' : '' }}>Binus Bandung</option>
                        <option value="Binus Jakarta" {{ old('campus', $student->campus) == 'Binus Jakarta' ? 'selected' : '' }}>Binus Jakarta</option>
                        <option value="Binus Bekasi" {{ old('campus', $student->campus) == 'Binus Bekasi' ? 'selected' : '' }}>Binus Bekasi</option>
                    </select>
                    @error('campus')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Faculty -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Faculty/School <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="faculty" 
                        value="{{ old('faculty', $student->faculty) }}"
                        class="w-full px-4 py-2 border rounded-lg"
                        required
                    >
                    @error('faculty')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Program -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Program <span class="text-red-500">*</span>
                    </label>
                    <select name="program" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">Pilih Program</option>
                        <option value="Computer Science" {{ old('program', $student->program) == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                        <option value="Visual Communication Design" {{ old('program', $student->program) == 'Visual Communication Design' ? 'selected' : '' }}>Visual Communication Design</option>
                        <option value="Interior Design" {{ old('program', $student->program) == 'Interior Design' ? 'selected' : '' }}>Interior Design</option>
                        <option value="Creativepreneurship" {{ old('program', $student->program) == 'Creativepreneurship' ? 'selected' : '' }}>Creativepreneurship</option>
                        <option value="Digital Business Innovation" {{ old('program', $student->program) == 'Digital Business Innovation' ? 'selected' : '' }}>Digital Business Innovation</option>
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
                        value="{{ old('binusian_id', $student->binusian_id) }}"
                        class="w-full px-4 py-2 border rounded-lg"
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
                        value="{{ old('nim', $student->nim) }}"
                        class="w-full px-4 py-2 border rounded-lg"
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
                        value="{{ old('binusian', $student->binusian) }}"
                        class="w-full px-4 py-2 border rounded-lg"
                        required
                    >
                    @error('binusian')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $student->name) }}"
                        class="w-full px-4 py-2 border rounded-lg"
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
                    <select name="tindak_lanjut" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">Pilih Status</option>
                        <option value="Merespon" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Merespon' ? 'selected' : '' }}>Merespon</option>
                        <option value="Mengajukan Undur Diri/DO" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Mengajukan Undur Diri/DO' ? 'selected' : '' }}>Mengajukan Undur Diri/DO</option>
                        <option value="Mengajukan Pindah Jurusan" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Mengajukan Pindah Jurusan' ? 'selected' : '' }}>Mengajukan Pindah Jurusan</option>
                        <option value="Data Pengajuan Reactive 25.2" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Data Pengajuan Reactive 25.2' ? 'selected' : '' }}>Data Pengajuan Reactive 25.2</option>
                        <option value="Sudah Terdata Aktif 25.2" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Sudah Terdata Aktif 25.2' ? 'selected' : '' }}>Sudah Terdata Aktif 25.2</option>
                        <option value="Terhubung Tapi Tidak Merespon" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Terhubung Tapi Tidak Merespon' ? 'selected' : '' }}>Terhubung Tapi Tidak Merespon</option>
                        <option value="Belum Terhubung" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Belum Terhubung' ? 'selected' : '' }}>Belum Terhubung</option>
                        <option value="Tidak Realistis 7 Tahun" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Tidak Realistis 7 Tahun' ? 'selected' : '' }}>Tidak Realistis 7 Tahun</option>
                        <option value="Dalam Proses Reactive" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Dalam Proses Reactive' ? 'selected' : '' }}>Dalam Proses Reactive</option>
                        <option value="Unofficial Leave" {{ old('tindak_lanjut', $student->tindak_lanjut) == 'Unofficial Leave' ? 'selected' : '' }}>Unofficial Leave</option>
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
                    <select name="evaluasi" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">Pilih Evaluasi</option>
                        <option value="Reguler" {{ old('evaluasi', $student->evaluasi) == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="Non Reguler" {{ old('evaluasi', $student->evaluasi) == 'Non Reguler' ? 'selected' : '' }}>Non Reguler</option>
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
                        value="{{ old('sks_kumulatif', $student->sks_kumulatif) }}"
                        class="w-full px-4 py-2 border rounded-lg"
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
                        value="{{ old('sks_sisa', $student->sks_sisa) }}"
                        class="w-full px-4 py-2 border rounded-lg"
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
                        value="{{ old('study_target_10', $student->study_target_10) }}"
                        class="w-full px-4 py-2 border rounded-lg"
                    >
                </div>

                <!-- Study Target 14 SMT -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Study Target 14 SMT
                    </label>
                    <input 
                        type="text" 
                        name="study_target_14" 
                        value="{{ old('study_target_14', $student->study_target_14) }}"
                        class="w-full px-4 py-2 border rounded-lg"
                    >
                </div>

                <!-- Prediksi SMT Selesai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Prediksi SMT Selesai
                    </label>
                    <input 
                        type="text" 
                        name="prediksi_smt_selesai" 
                        value="{{ old('prediksi_smt_selesai', $student->prediksi_smt_selesai) }}"
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
                        value="{{ old('no_hp', $student->no_hp) }}"
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
                    >{{ old('notes_srsc', $student->notes_srsc) }}</textarea>
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan / Catatan Tambahan
                    </label>
                    <textarea 
                        name="keterangan" 
                        rows="3"
                        class="w-full px-4 py-2 border rounded-lg"
                        placeholder="Catatan tambahan follow up..."
                    >{{ old('keterangan', $student->keterangan) }}</textarea>
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                <a href="{{ route('students.show', $student) }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection