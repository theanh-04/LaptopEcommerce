@extends('layouts.admin')

@section('title', 'Quản lý Nhân sự')

@section('content')
<!-- Header -->
<header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <h1 class="text-4xl font-bold tracking-tight text-on-background mb-2">Quản lý Nhân sự</h1>
        <p class="text-on-surface-variant max-w-lg">Điều phối đội ngũ kỹ thuật và vận hành hệ thống tại trung tâm Neon Kinetic.</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
            <input id="searchInput" onkeyup="searchEmployees()" class="bg-white border border-gray-300 rounded-xl py-3 pl-12 pr-6 text-sm focus:ring-1 focus:ring-cyan-400 w-64 md:w-80 text-gray-900 placeholder-gray-400" placeholder="Tìm kiếm nhân viên..." type="text"/>
        </div>
        <div class="relative">
            <select id="departmentFilter" onchange="searchEmployees()" class="bg-white border border-outline-variant/15 rounded-xl py-3 pl-6 pr-10 text-sm focus:ring-1 focus:ring-primary text-gray-900 cursor-pointer">
                <option value="all">Tất cả phòng ban</option>
                @foreach($departments ?? [] as $dept)
                <option value="{{ $dept }}">{{ $dept }}</option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-neutral-500 pointer-events-none">expand_more</span>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-bold text-sm hover:bg-primary/90 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span>
            Thêm nhân viên
        </a>
    </div>
</header>

<!-- Stats Bento -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <div class="surface-container-low rounded-xl p-6 glass-panel relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-primary/10"></div>
        <div class="flex justify-between items-start mb-4">
            <span class="material-symbols-outlined text-primary text-3xl">groups</span>
            <span class="text-xs font-bold text-secondary uppercase tracking-widest">Total</span>
        </div>
        <div class="text-4xl font-bold text-on-background mb-1">{{ $totalEmployees ?? 0 }}</div>
        <div class="text-sm text-on-surface-variant font-medium">Nhân viên hệ thống</div>
    </div>

    <div class="surface-container-low rounded-xl p-6 glass-panel relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 rounded-full blur-3xl -mr-16 -mt-16 transition-all"></div>
        <div class="flex justify-between items-start mb-4">
            <span class="material-symbols-outlined text-primary-fixed text-3xl" style="font-variation-settings: 'FILL' 1;">bolt</span>
            <div class="flex items-center gap-1.5 bg-primary/10 px-2 py-0.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-fixed shadow-[0_0_8px_#00f4fe]"></span>
                <span class="text-[10px] font-bold text-primary-fixed uppercase tracking-wider">Live</span>
            </div>
        </div>
        <div class="text-4xl font-bold text-on-background mb-1">{{ $onlineCount ?? 0 }}</div>
        <div class="text-sm text-on-surface-variant font-medium">Đang trực tuyến</div>
    </div>

    <div class="surface-container-low rounded-xl p-6 glass-panel relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-secondary/10"></div>
        <div class="flex justify-between items-start mb-4">
            <span class="material-symbols-outlined text-secondary text-3xl">hub</span>
            <span class="text-xs font-bold text-secondary uppercase tracking-widest">Units</span>
        </div>
        <div class="text-4xl font-bold text-on-background mb-1">{{ $departmentCount ?? 0 }}</div>
        <div class="text-sm text-on-surface-variant font-medium">Phòng ban vận hành</div>
    </div>
</section>

<!-- Employee Table -->
<section class="surface-container-low rounded-xl overflow-hidden glass-panel">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-high/50 text-on-surface-variant text-xs uppercase tracking-widest font-bold">
                    <th class="px-8 py-5">Nhân viên</th>
                    <th class="px-6 py-5">Chức vụ</th>
                    <th class="px-6 py-5">Trạng thái</th>
                    <th class="px-6 py-5">Đơn hàng</th>
                    <th class="px-8 py-5 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10" id="employeeTableBody">
                @foreach($employees ?? [] as $employee)
                <tr class="hover:bg-surface-container-high/40 transition-colors" data-employee-id="{{ $employee->id }}">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-sm">person</span>
                                </div>
                                @if($employee->is_online ?? false)
                                <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-primary-fixed rounded-full border-2 border-surface shadow-[0_0_5px_#00f4fe]"></span>
                                @else
                                <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-neutral-600 rounded-full border-2 border-surface"></span>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-on-background {{ !($employee->is_online ?? false) ? 'opacity-60' : '' }}">{{ $employee->name }}</div>
                                <div class="text-xs text-on-surface-variant">{{ $employee->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="bg-surface-container-highest px-3 py-1 rounded-sm text-xs font-medium text-tertiary">{{ $employee->position ?? 'Staff' }}</span>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium {{ ($employee->is_online ?? false) ? 'text-primary-fixed' : 'text-on-surface-variant' }}">
                                {{ ($employee->is_online ?? false) ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-sm">shopping_bag</span>
                            <span class="text-sm font-bold text-on-background">{{ $employee->orders_count ?? 0 }}</span>
                            <span class="text-xs text-on-surface-variant">đơn</span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.employees.edit', $employee->id) }}" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-primary">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                            <button onclick="deleteEmployee({{ $employee->id }})" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-error">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-8 py-4 bg-surface-container-low/50 border-t border-outline-variant/10 flex items-center justify-between">
        <span class="text-xs text-on-surface-variant">Hiển thị 1-4 trong số 42 nhân viên</span>
        <div class="flex gap-2">
            <button class="p-2 hover:bg-surface-container-highest rounded text-on-surface-variant transition-all">
                <span class="material-symbols-outlined text-sm">chevron_left</span>
            </button>
            <button class="px-3 py-1 bg-primary text-on-primary rounded text-xs font-bold">1</button>
            <button class="px-3 py-1 hover:bg-surface-container-highest rounded text-xs font-medium text-on-surface-variant">2</button>
            <button class="px-3 py-1 hover:bg-surface-container-highest rounded text-xs font-medium text-on-surface-variant">3</button>
            <button class="p-2 hover:bg-surface-container-highest rounded text-on-surface-variant transition-all">
                <span class="material-symbols-outlined text-sm">chevron_right</span>
            </button>
        </div>
    </div>
</section>

@push('styles')
<style>
.glass-panel {
    background: rgba(38, 38, 38, 0.4);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(72, 72, 71, 0.15);
}

/* Hide default select arrow */
select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: none;
}

select::-ms-expand {
    display: none;
}
</style>
@endpush

@push('scripts')
<script>
function searchEmployees() {
    const search = document.getElementById('searchInput').value;
    const department = document.getElementById('departmentFilter').value;
    
    fetch(`{{ route('admin.employees.search') }}?search=${search}&department=${department}`)
        .then(response => response.json())
        .then(data => {
            renderEmployees(data);
        });
}

function renderEmployees(employees) {
    const tbody = document.getElementById('employeeTableBody');
    
    if (employees.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-8 py-10 text-center text-on-surface-variant">Không tìm thấy nhân viên nào</td></tr>';
        return;
    }
    
    let html = '';
    employees.forEach(emp => {
        const onlineClass = emp.is_online ? 'text-primary-fixed' : 'text-on-surface-variant';
        const opacityClass = !emp.is_online ? 'opacity-60' : '';
        
        html += `
            <tr class="hover:bg-surface-container-high/40 transition-colors" data-employee-id="${emp.id}">
                <td class="px-8 py-5">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-sm">person</span>
                            </div>
                            ${emp.is_online 
                                ? '<span class="absolute -bottom-1 -right-1 w-3 h-3 bg-primary-fixed rounded-full border-2 border-surface shadow-[0_0_5px_#00f4fe]"></span>'
                                : '<span class="absolute -bottom-1 -right-1 w-3 h-3 bg-neutral-600 rounded-full border-2 border-surface"></span>'
                            }
                        </div>
                        <div>
                            <div class="font-bold text-on-background ${opacityClass}">${emp.name}</div>
                            <div class="text-xs text-on-surface-variant">${emp.email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <span class="bg-surface-container-highest px-3 py-1 rounded-sm text-xs font-medium text-tertiary">${emp.position}</span>
                </td>
                <td class="px-6 py-5">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium ${onlineClass}">
                            ${emp.is_online ? 'Online' : 'Offline'}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-sm">shopping_bag</span>
                        <span class="text-sm font-bold text-on-background">${emp.orders_count || 0}</span>
                        <span class="text-xs text-on-surface-variant">đơn</span>
                    </div>
                </td>
                <td class="px-8 py-5 text-right">
                    <div class="flex justify-end gap-3">
                        <a href="/admin/employees/${emp.id}/edit" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-primary">
                            <span class="material-symbols-outlined text-xl">edit</span>
                        </a>
                        <button onclick="deleteEmployee(${emp.id})" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-error">
                            <span class="material-symbols-outlined text-xl">delete</span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

function deleteEmployee(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa nhân viên này?')) return;
    
    fetch(`/admin/employees/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`tr[data-employee-id="${id}"]`).remove();
            alert(data.message);
        } else {
            alert('Có lỗi xảy ra');
        }
    });
}
</script>
@endpush
@endsection
