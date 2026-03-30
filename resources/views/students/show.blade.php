@extends('layouts.app')

@section('page-title', 'Detail Mahasiswa')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('students.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Mahasiswa
        </a>
    </div>

    <!-- Student Info Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">{{ $student->name }}</h2>
                <p class="text-blue-100 mt-1">{{ $student->nim }} • {{ $student->binusian_id }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('students.edit', $student) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin ingin menghapus data {{ $student->name }}?')" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Section: Informasi Akademik -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                        Informasi Akademik
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-600">Admit Term</label>
                            <p class="font-medium text-gray-900">{{ $student->admit_term }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">Campus</label>
                            <p class="font-medium text-gray-900">{{ $student->campus }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">Faculty/School</label>
                            <p class="font-medium text-gray-900">{{ $student->faculty }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">Program Studi</label>
                            <p class="font-medium text-gray-900">{{ $student->program }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">Binusian</label>
                            <p class="font-medium text-gray-900">{{ $student->binusian }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Status & Follow Up -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-tasks text-green-600 mr-2"></i>
                        Status & Follow Up
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-600">Tindak Lanjut SRSC</label>
                            <p class="font-medium">
                                <span class="px-3 py-1 rounded-full text-sm
                                    {{ $student->tindak_lanjut == 'Merespon' ? 'bg-green-100 text-green-800' : 
                                       ($student->tindak_lanjut == 'Unofficial Leave' ? 'bg-red-100 text-red-800' : 
                                       'bg-blue-100 text-blue-800') }}">
                                    {{ $student->tindak_lanjut }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">Evaluasi</label>
                            <p class="font-medium text-gray-900">{{ $student->evaluasi }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">SKS Kumulatif</label>
                            <p class="font-medium text-gray-900">{{ $student->sks_kumulatif }} SKS</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">SKS Sisa</label>
                            <p class="font-medium text-gray-900">{{ $student->sks_sisa }} SKS</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">Study Target 10 SMT</label>
                            <p class="font-medium text-gray-900">{{ $student->study_target_10 ?: '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">Study Target 14 SMT</label>
                            <p class="font-medium text-gray-900">{{ $student->study_target_14 ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Informasi Tambahan -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                        Informasi Tambahan
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-600">Prediksi SMT Selesai</label>
                            <p class="font-medium text-gray-900">{{ $student->prediksi_smt_selesai ?: '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-600">No. HP Mahasiswa</label>
                            <p class="font-medium text-gray-900 font-mono">
                                @if($student->no_hp && $student->no_hp != '#N/A')
                                    <a href="tel:{{ $student->no_hp }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-phone mr-1"></i>
                                        {{ $student->no_hp }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section: Notes -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                        Catatan
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-600">Notes SRSC</label>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $student->notes_srsc ?: '-' }}</p>
                        </div>
                        
                        @if($student->keterangan)
                        <div>
                            <label class="text-sm text-gray-600">Keterangan</label>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $student->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Timestamp -->
            <div class="mt-6 pt-6 border-t text-sm text-gray-500">
                <div class="flex justify-between">
                    <div>
                        <i class="far fa-clock mr-1"></i>
                        Dibuat: {{ $student->created_at->format('d M Y, H:i') }}
                    </div>
                    <div>
                        <i class="far fa-clock mr-1"></i>
                        Terakhir diupdate: {{ $student->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection