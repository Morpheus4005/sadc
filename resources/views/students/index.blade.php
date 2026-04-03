@extends('layouts.app')

@section('page-title', 'Data Mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold">Total: {{ $students->total() }} mahasiswa</h3>
        </div>
        <div class="flex gap-3">
            @if($students->total() > 0)
            <button onclick="openDeleteAllModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-trash-alt mr-2"></i>
                Delete All Data
            </button>
            @endif
            
            <a href="{{ route('students.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Tambah Mahasiswa
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Box -->
            <input 
                type="text" 
                name="search" 
                placeholder="Cari nama, NIM, atau Binusian ID..."
                value="{{ request('search') }}"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
            
            <!-- Filter Program -->
            <select name="program" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Program</option>
                @foreach($programs as $program)
                    <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>
                        {{ $program }}
                    </option>
                @endforeach
            </select>
            
            <!-- Filter Status dengan Warna -->
            <div class="relative">
                <select 
                    name="status_warna" 
                    class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full appearance-none"
                    onchange="updateFilterColor(this)"
                >
                    <option value="">Semua Status</option>
                    <option value="Proses Re-active" {{ request('status_warna') == 'Proses Re-active' ? 'selected' : '' }} data-color="cyan">
                        🔵 Proses Re-active
                    </option>
                    <option value="Re-active" {{ request('status_warna') == 'Re-active' ? 'selected' : '' }} data-color="green">
                        🟢 Re-active
                    </option>
                    <option value="Merespon tapi belum re-active" {{ request('status_warna') == 'Merespon tapi belum re-active' ? 'selected' : '' }} data-color="purple">
                        🟣 Merespon tapi belum re-active
                    </option>
                    <option value="Undur Diri" {{ request('status_warna') == 'Undur Diri' ? 'selected' : '' }} data-color="amber">
                        🟤 Undur Diri
                    </option>
                    <option value="Tidak Terhubung" {{ request('status_warna') == 'Tidak Terhubung' ? 'selected' : '' }} data-color="red">
                        🔴 Tidak Terhubung
                    </option>
                    <option value="Terhubung Tapi Tidak Merespon" {{ request('status_warna') == 'Terhubung Tapi Tidak Merespon' ? 'selected' : '' }} data-color="yellow">
                        🟡 Terhubung Tapi Tidak Merespon
                    </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('students.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

        <!-- Status Legend dengan Counter -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">
                <i class="fas fa-palette mr-2"></i>
                Legend Status (Periode: {{ \App\Helpers\PeriodeHelper::getCurrentPeriode() }}):
            </h4>
            <div class="flex flex-wrap gap-3">
                @php
                    $currentPeriode = \App\Helpers\PeriodeHelper::getCurrentPeriode();
                    $statusCounts = \App\Models\Student::where('periode_akademik', $currentPeriode)
                        ->select('status_warna', \DB::raw('count(*) as total'))
                        ->whereNotNull('status_warna')
                        ->groupBy('status_warna')
                        ->pluck('total', 'status_warna');
                @endphp
                
                <a href="{{ route('students.index', ['status_warna' => 'Proses Re-active']) }}" 
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border-2 bg-cyan-100 text-cyan-800 border-cyan-300 hover:bg-cyan-200 transition">
                    <span class="w-3 h-3 rounded-full bg-cyan-400 inline-block mr-1"></span>
                    Proses Re-active
                    <span class="ml-1 bg-cyan-200 px-1.5 py-0.5 rounded-full text-xs">{{ $statusCounts['Proses Re-active'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('students.index', ['status_warna' => 'Re-active']) }}" 
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border-2 bg-green-100 text-green-800 border-green-300 hover:bg-green-200 transition">
                    <span class="w-3 h-3 rounded-full bg-green-400 inline-block mr-1"></span>
                    Re-active
                    <span class="ml-1 bg-green-200 px-1.5 py-0.5 rounded-full text-xs">{{ $statusCounts['Re-active'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('students.index', ['status_warna' => 'Merespon tapi belum re-active']) }}" 
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border-2 bg-purple-100 text-purple-800 border-purple-300 hover:bg-purple-200 transition">
                    <span class="w-3 h-3 rounded-full bg-purple-400 inline-block mr-1"></span>
                    Merespon tapi belum re-active
                    <span class="ml-1 bg-purple-200 px-1.5 py-0.5 rounded-full text-xs">{{ $statusCounts['Merespon tapi belum re-active'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('students.index', ['status_warna' => 'Undur Diri']) }}" 
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border-2 bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100 transition">
                    <span class="w-3 h-3 rounded-full bg-amber-200 inline-block mr-1"></span>
                    Undur Diri
                    <span class="ml-1 bg-amber-200 px-1.5 py-0.5 rounded-full text-xs">{{ $statusCounts['Undur Diri'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('students.index', ['status_warna' => 'Tidak Terhubung']) }}" 
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border-2 bg-red-100 text-red-800 border-red-300 hover:bg-red-200 transition">
                    <span class="w-3 h-3 rounded-full bg-red-400 inline-block mr-1"></span>
                    Tidak Terhubung
                    <span class="ml-1 bg-red-200 px-1.5 py-0.5 rounded-full text-xs">{{ $statusCounts['Tidak Terhubung'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('students.index', ['status_warna' => 'Terhubung Tapi Tidak Merespon']) }}" 
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border-2 bg-yellow-100 text-yellow-800 border-yellow-300 hover:bg-yellow-200 transition">
                    <span class="w-3 h-3 rounded-full bg-yellow-400 inline-block mr-1"></span>
                    Terhubung Tapi Tidak Merespon
                    <span class="ml-1 bg-yellow-200 px-1.5 py-0.5 rounded-full text-xs">{{ $statusCounts['Terhubung Tapi Tidak Merespon'] ?? 0 }}</span>
                </a>
            </div>
        </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($students->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed">
                <colgroup>
                    <col style="width: 40px;">   <!-- No -->
                    <col style="width: 110px;">  <!-- Binusian ID -->
                    <col style="width: 100px;">  <!-- NIM -->
                    <col style="width: 180px;">  <!-- Nama -->
                    <col style="width: 150px;">  <!-- Program -->
                    <col style="width: 80px;">   <!-- Evaluasi -->
                    <col style="width: 80px;">   <!-- SKS -->
                    <col style="width: 180px;">  <!-- Status Warna -->
                    <col style="width: 180px;">  <!-- Tindak Lanjut -->
                    <col style="width: 100px;">  <!-- Aksi -->
                </colgroup>
                
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Binusian ID</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluasi</th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">SKS</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tindak Lanjut</th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($students as $index => $student)
                    <tr class="hover:bg-gray-50">
                        <!-- No -->
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $students->firstItem() + $index }}
                        </td>
                        
                        <!-- Binusian ID -->
                        <td class="px-3 py-3 whitespace-nowrap text-xs font-mono text-gray-600">
                            {{ $student->binusian_id }}
                        </td>
                        
                        <!-- NIM -->
                        <td class="px-3 py-3 whitespace-nowrap text-xs font-mono text-gray-900">
                            {{ $student->nim }}
                        </td>
                        
                        <!-- Nama (dengan word wrap) -->
                        <td class="px-3 py-3 text-sm font-medium text-gray-900">
                            <div class="break-words max-w-[180px]">
                                {{ $student->name }}
                            </div>
                        </td>
                        
                        <!-- Program (dengan word wrap) -->
                        <td class="px-3 py-3 text-xs text-gray-600">
                            <div class="break-words max-w-[150px]">
                                {{ $student->program }}
                            </div>
                        </td>
                        
                        <!-- Evaluasi -->
                        <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-600">
                            {{ $student->evaluasi }}
                        </td>
                        
                        <!-- SKS Kumulatif -->
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-center text-gray-900 font-semibold">
                            {{ $student->sks_kumulatif }}
                        </td>
                        
                        <!-- STATUS WARNA (dengan dropdown) -->
                        <td class="px-3 py-3">
                            <div class="relative status-dropdown-container-{{ $student->id }}">
                                <button 
                                    onclick="toggleStatusDropdown({{ $student->id }})" 
                                    class="w-full px-2 py-1.5 text-xs font-semibold rounded-lg border-2 cursor-pointer hover:opacity-80 transition text-left {{ $student->status_warna ? $student->getStatusColorClass() : 'bg-gray-100 text-gray-600 border-gray-300' }}"
                                    id="status-button-{{ $student->id }}"
                                >
                                    <div class="truncate">
                                        {{ $student->status_warna ?: 'Set Status' }}
                                    </div>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div 
                                    id="status-dropdown-{{ $student->id }}" 
                                    class="hidden absolute z-10 mt-1 w-64 bg-white rounded-lg shadow-lg border border-gray-200 left-0"
                                >
                                    <div class="py-1">
                                        <button 
                                            onclick="updateStatus({{ $student->id }}, 'Proses Re-active')" 
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center"
                                        >
                                            <span class="w-3 h-3 rounded-full bg-cyan-400 mr-2"></span>
                                            Proses Re-active
                                        </button>
                                        <button 
                                            onclick="updateStatus({{ $student->id }}, 'Re-active')" 
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center"
                                        >
                                            <span class="w-3 h-3 rounded-full bg-green-400 mr-2"></span>
                                            Re-active
                                        </button>
                                        <button 
                                            onclick="updateStatus({{ $student->id }}, 'Merespon tapi belum re-active')" 
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center"
                                        >
                                            <span class="w-3 h-3 rounded-full bg-purple-400 mr-2"></span>
                                            Merespon tapi belum re-active
                                        </button>
                                        <button 
                                            onclick="updateStatus({{ $student->id }}, 'Undur Diri')" 
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center"
                                        >
                                            <span class="w-3 h-3 rounded-full bg-amber-200 mr-2"></span>
                                            Undur Diri
                                        </button>
                                        <button 
                                            onclick="updateStatus({{ $student->id }}, 'Tidak Terhubung')" 
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center"
                                        >
                                            <span class="w-3 h-3 rounded-full bg-red-400 mr-2"></span>
                                            Tidak Terhubung
                                        </button>
                                        <button 
                                            onclick="updateStatus({{ $student->id }}, 'Terhubung Tapi Tidak Merespon')" 
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center"
                                        >
                                            <span class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></span>
                                            Terhubung Tapi Tidak Merespon
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- TINDAK LANJUT -->
                        <td class="px-3 py-3">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $student->tindak_lanjut == 'Merespon' ? 'bg-green-100 text-green-800' : 
                                ($student->tindak_lanjut == 'Unofficial Leave' ? 'bg-red-100 text-red-800' : 
                                'bg-blue-100 text-blue-800') }}">
                                <div class="truncate max-w-[150px]">
                                    {{ $student->tindak_lanjut }}
                                </div>
                            </span>
                        </td>
                        
                        <!-- AKSI (paling kanan) -->
                        <td class="px-3 py-3 whitespace-nowrap text-center text-sm">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Hapus data mahasiswa {{ $student->name }}?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $students->links() }}
        </div>
        @else
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-users text-6xl mb-4 text-gray-300"></i>
            <p class="text-lg">Belum ada data mahasiswa</p>
            <p class="text-sm mt-2">Import data Excel atau tambah manual untuk memulai</p>
        </div>
        @endif
    </div>
</div>

<!-- Delete All Modal -->
<div id="deleteAllModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <!-- Content -->
            <div class="mt-4 text-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    Hapus Semua Data?
                </h3>
                <p class="text-sm text-gray-500 mt-2">
                    Apakah Anda yakin ingin menghapus <strong>SEMUA</strong> data mahasiswa?
                </p>
                <p class="text-xs text-red-600 mt-2">
                    ⚠️ Tindakan ini akan menghapus data secara permanen dan tidak bisa dibatalkan!
                </p>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-3 mt-6">
                <button 
                    onclick="closeDeleteAllModal()"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition"
                >
                    Tidak
                </button>
                <form method="POST" action="{{ route('students.destroyAll') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="force_delete" value="1">
                    <button 
                        type="submit"
                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition"
                    >
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Show modal
function showDeleteAllModal() {
    document.getElementById('deleteAllModal').classList.remove('hidden');
}

// Close modal
function closeDeleteAllModal() {
    document.getElementById('deleteAllModal').classList.add('hidden');
}

// Close on outside click
document.getElementById('deleteAllModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteAllModal();
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteAllModal();
    }
});
</script>

<script>
// Update force delete input when checkbox changes
document.getElementById('forceDeleteCheckbox').addEventListener('change', function() {
    document.getElementById('forceDeleteInput').value = this.checked ? '1' : '0';
});

// Enable button when confirmation matches
document.getElementById('deleteConfirmation').addEventListener('input', function() {
    const button = document.getElementById('deleteAllButton');
    button.disabled = this.value !== 'DELETE ALL DATA';
});

function openDeleteAllModal() {
    document.getElementById('deleteAllModal').classList.remove('hidden');
    document.getElementById('deleteConfirmation').value = '';
    document.getElementById('deleteAllButton').disabled = true;
    document.getElementById('forceDeleteCheckbox').checked = true; // Default checked
    document.getElementById('forceDeleteInput').value = '1';
}

function closeDeleteAllModal() {
    document.getElementById('deleteAllModal').classList.add('hidden');
}
</script>

// Enable button only if confirmation text matches
document.getElementById('confirmationInput').addEventListener('input', function(e) {
    const button = document.getElementById('deleteAllButton');
    if (e.target.value === 'DELETE ALL DATA') {
        button.disabled = false;
        button.classList.remove('disabled:bg-gray-400', 'disabled:cursor-not-allowed');
    } else {
        button.disabled = true;
        button.classList.add('disabled:bg-gray-400', 'disabled:cursor-not-allowed');
    }
});

document.getElementById('deleteAllModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteAllModal();
    }
});
</script>

<script>
// Toggle dropdown
function toggleStatusDropdown(studentId) {
    const dropdown = document.getElementById(`status-dropdown-${studentId}`);
    
    // Close all other dropdowns
    document.querySelectorAll('[id^="status-dropdown-"]').forEach(el => {
        if (el.id !== `status-dropdown-${studentId}`) {
            el.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Update status via AJAX
async function updateStatus(studentId, newStatus) {
    const button = document.getElementById(`status-button-${studentId}`);
    const dropdown = document.getElementById(`status-dropdown-${studentId}`);
    
    // Close dropdown
    dropdown.classList.add('hidden');
    
    // Show loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/students/${studentId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                status_warna: newStatus
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update button
            button.className = `px-3 py-1.5 text-xs font-semibold rounded-lg border-2 cursor-pointer hover:opacity-80 transition ${data.colorClass}`;
            button.innerHTML = `${newStatus} <i class="fas fa-chevron-down ml-1 text-xs"></i>`;
            button.disabled = false;
            
            // Show success toast (optional)
            showToast('Status berhasil diupdate!', 'success');
        } else {
            throw new Error(data.message || 'Update gagal');
        }
    } catch (error) {
        console.error('Error:', error);
        button.innerHTML = 'Error - Klik untuk retry <i class="fas fa-chevron-down ml-1 text-xs"></i>';
        button.disabled = false;
        showToast('Gagal update status', 'error');
    }
}

// Simple toast notification
function showToast(message, type = 'success') {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'times'}-circle mr-2"></i>${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[class*="status-dropdown-container"]')) {
        document.querySelectorAll('[id^="status-dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});
</script>

<!-- Auto-Refresh System -->
<div id="update-indicator" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center">
    <i class="fas fa-sync-alt mr-2 animate-spin"></i>
    <span>Data updated!</span>
</div>

<div id="sync-status" class="fixed top-4 right-4 bg-white px-3 py-2 rounded-lg shadow text-xs text-gray-600 z-40">
    <i class="fas fa-circle text-green-500 mr-1"></i>
    <span id="sync-text">Live</span>
</div>

<script>
let lastUpdateTime = Date.now();
let refreshInterval = 5000;
let isRefreshing = false;

// Auto-refresh function
async function autoRefreshTable() {
    if (isRefreshing) return;
    
    isRefreshing = true;
    updateSyncStatus('syncing');
    
    try {
        // Fetch updated data
        const response = await fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const html = await response.text();
        
        // Parse HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Get new table body
        const newTableBody = doc.querySelector('table tbody');
        const oldTableBody = document.querySelector('table tbody');
        
        // Get new stats
        const newStats = doc.querySelector('h3');
        const oldStats = document.querySelector('h3');
        
        if (newTableBody && oldTableBody) {
            // Check if content changed
            if (newTableBody.innerHTML !== oldTableBody.innerHTML) {
                // Update table
                oldTableBody.innerHTML = newTableBody.innerHTML;
                
                // Update stats
                if (newStats && oldStats) {
                    oldStats.textContent = newStats.textContent;
                }
                
                // Show update indicator
                showUpdateIndicator();
                
                // Re-attach event listeners for new dropdowns
                reattachEventListeners();
            }
        }
        
        lastUpdateTime = Date.now();
        updateSyncStatus('live');
        
    } catch (error) {
        console.error('Auto-refresh error:', error);
        updateSyncStatus('error');
    } finally {
        isRefreshing = false;
    }
}

// Update sync status indicator
function updateSyncStatus(status) {
    const statusIcon = document.querySelector('#sync-status i');
    const statusText = document.querySelector('#sync-text');
    
    if (status === 'syncing') {
        statusIcon.className = 'fas fa-sync-alt fa-spin text-blue-500 mr-1';
        statusText.textContent = 'Syncing...';
    } else if (status === 'live') {
        statusIcon.className = 'fas fa-circle text-green-500 mr-1';
        statusText.textContent = 'Live';
    } else if (status === 'error') {
        statusIcon.className = 'fas fa-exclamation-circle text-red-500 mr-1';
        statusText.textContent = 'Error';
    }
}

// Show update notification
function showUpdateIndicator() {
    const indicator = document.getElementById('update-indicator');
    indicator.classList.remove('hidden');
    
    setTimeout(() => {
        indicator.classList.add('hidden');
    }, 3000);
}

// Re-attach event listeners after refresh
function reattachEventListeners() {
    // Close all dropdowns
    document.querySelectorAll('[id^="status-dropdown-"]').forEach(dropdown => {
        dropdown.classList.add('hidden');
    });
}

// Start auto-refresh
setInterval(autoRefreshTable, refreshInterval);

// Show initial status
updateSyncStatus('live');

// Pause refresh when user is editing
let pauseRefresh = false;
document.addEventListener('click', function(e) {
    // If user clicks on status dropdown, pause refresh for 30 seconds
    if (e.target.closest('[id^="status-dropdown-"]') || e.target.closest('[id^="status-button-"]')) {
        pauseRefresh = true;
        updateSyncStatus('paused');
        
        setTimeout(() => {
            pauseRefresh = false;
            updateSyncStatus('live');
        }, 30000); // Resume after 30 seconds
    }
});

// Modified refresh to respect pause
const originalRefresh = autoRefreshTable;
autoRefreshTable = function() {
    if (!pauseRefresh) {
        originalRefresh();
    }
};

console.log('✓ Auto-refresh enabled (every 15 seconds)');
</script>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endsection