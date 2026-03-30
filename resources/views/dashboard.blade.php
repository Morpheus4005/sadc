@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Mahasiswa</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalStudents }}</h3>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-3xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Program Studi</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $recapByProgram->count() }}</h3>
                    <p class="text-sm text-gray-500 mt-2">Program aktif</p>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-3xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Update Terakhir</p>
                    <h3 class="text-lg font-bold text-gray-800 mt-2">
                        @if($latestSnapshot)
                            {{ $latestSnapshot->snapshot_date->format('d M Y') }}
                        @else
                            Belum ada
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500 mt-2">
                        @if($latestSnapshot)
                            {{ $latestSnapshot->snapshot_date->diffForHumans() }}
                        @else
                            Import data pertama
                        @endif
                    </p>
                </div>
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="far fa-calendar-check text-3xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Update Berikutnya</p>
                    <h3 class="text-lg font-bold text-gray-800 mt-2">
                        {{ now()->next('Friday')->format('d M Y') }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-2">
                        {{ now()->next('Friday')->diffForHumans() }}
                    </p>
                </div>
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="far fa-clock text-3xl text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800">Rekap Data Mahasiswa</h2>
                <p class="text-gray-600 mt-2">Binus University Bandung</p>
            </div>
            <div>
            <a href="{{ route('students.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>
                Tambah Mahasiswa Baru
              </a>
              <a href="{{ route('import') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-file-upload mr-2"></i>
                Import Excel
            </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rekap per Program</h3>
            <div class="space-y-2">
                @forelse($recapByProgram as $item)
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded">
                    <span class="text-sm text-gray-700">{{ $item->program }}</span>
                    <span class="font-bold text-blue-600">{{ $item->count }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada data</p>
                @endforelse
                
                @if($recapByProgram->count() > 0)
                <div class="flex justify-between items-center bg-blue-600 text-white p-3 rounded font-bold mt-4">
                    <span>TOTAL</span>
                    <span>{{ $totalStudents }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rekap per Status</h3>
            <div class="space-y-2">
                @forelse($recapByStatus as $item)
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded">
                    <span class="text-sm text-gray-700">{{ $item->tindak_lanjut }}</span>
                    <span class="font-bold text-green-600">{{ $item->count }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

@if($totalStudents == 0)
<div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded">
    <h3 class="font-semibold text-blue-900 mb-2">Memulai</h3>
    <p class="text-blue-800 mb-4">Belum ada data mahasiswa. Mulai dengan:</p>
    <ol class="list-decimal list-inside text-blue-800 space-y-2">
        <li>Klik menu "Data Mahasiswa" di sidebar</li>
        <li>Klik tombol "Tambah Mahasiswa"</li>
        <li>Isi form dengan data mahasiswa</li>
        <li>Klik tombol "Simpan Data"</li>
    </ol>
</div>
@endif
</div>
@endsection