@extends('layouts.app')

@section('page-title', 'Manajemen Periode Akademik')

@section('content')

    <!-- Action Bar -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt mr-2"></i>
            Manajemen Periode Akademik
        </h2>
        <a 
            href="{{ route('periode.create') }}" 
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
        >
            <i class="fas fa-plus mr-2"></i>
            Tambah Periode Baru
        </a>
    </div>

    <!-- Current Periode Info -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 text-2xl mr-4"></i>
            <div>
                <h3 class="font-semibold text-blue-900">Periode Aktif Saat Ini:</h3>
                <p class="text-2xl font-bold text-blue-700 mt-1">{{ $currentPeriode }}</p>
            </div>
        </div>
    </div>

    <!-- Periode Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($periodes as $periodeItem)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden {{ $periodeItem->nama_periode === $currentPeriode ? 'ring-4 ring-blue-500' : '' }}">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">{{ $periodeItem->nama_periode }}</h3>
                        <p class="text-blue-100 text-sm mt-1">
                            @if(Str::contains($periodeItem->semester, 'Ganjil'))
                                📚 Semester Ganjil
                            @else
                                📖 Semester Genap
                            @endif
                        </p>
                    </div>
                    @if($periodeItem->nama_periode === $currentPeriode)
                        <span class="bg-white text-blue-600 px-3 py-1 rounded-full text-xs font-bold">
                            ✓ AKTIF
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6">
                <!-- Student Count -->
                <div class="mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Mahasiswa:</span>
                        <span class="text-2xl font-bold text-blue-600">
                            {{ $periodeCounts[$periodeItem->nama_periode] ?? 0 }}
                        </span>
                    </div>
                </div>

                <!-- Info -->
                @if($periodeItem->keterangan)
                <div class="mb-4 p-3 bg-gray-50 rounded text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ $periodeItem->keterangan }}
                </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-3">
                    @if($periodeItem->nama_periode !== $currentPeriode)
                    <form action="{{ route('periode.switch') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="periode" value="{{ $periodeItem->nama_periode }}">
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            onclick="return confirm('Beralih ke periode {{ $periodeItem->nama_periode }}?')"
                        >
                            <i class="fas fa-exchange-alt mr-2"></i>
                            Aktifkan Periode
                        </button>
                    </form>
                    
                    <!-- Delete Button -->
                    @if(($periodeCounts[$periodeItem->nama_periode] ?? 0) == 0)
                    <form action="{{ route('periode.destroy', $periodeItem->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                            onclick="return confirm('Hapus periode {{ $periodeItem->nama_periode }}?')"
                            title="Hapus Periode"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                    
                    @else
                    <a 
                        href="{{ route('students.index') }}" 
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center"
                    >
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Data
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Info Box -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg mt-6">
        <div class="flex">
            <i class="fas fa-lightbulb text-yellow-500 text-xl mr-4"></i>
            <div class="text-sm text-yellow-800">
                <h4 class="font-semibold mb-2">💡 Cara Kerja Periode:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Setiap periode memiliki data mahasiswa yang <strong>terpisah</strong></li>
                    <li>Data dari periode satu <strong>tidak akan tercampur</strong> dengan periode lainnya</li>
                    <li>Klik <strong>"Aktifkan Periode"</strong> untuk beralih antar periode</li>
                    <li>Import Excel otomatis masuk ke <strong>periode yang sedang aktif</strong></li>
                    <li>Dashboard, Data Mahasiswa, dan Export hanya tampilkan data dari <strong>periode aktif</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection