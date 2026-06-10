@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'المستخدمون' : 'Users')
@section('page-title', app()->getLocale() === 'ar' ? 'المستخدمون' : 'Users')

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
                    <i class="fa-solid fa-house text-xs"></i>
                </a>
                <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
                <span class="text-gray-900 font-medium">{{ app()->getLocale() === 'ar' ? 'المستخدمون' : 'Users' }}</span>
            </nav>
            <h2 class="text-xl font-bold text-gray-900">{{ app()->getLocale() === 'ar' ? 'إدارة المستخدمين' : 'User Management' }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $users->total() }} {{ app()->getLocale() === 'ar' ? 'نتيجة' : 'results' }}</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            {{ app()->getLocale() === 'ar' ? 'إضافة مستخدم' : 'Add User' }}
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 flex items-center gap-3">
            <i class="fa-solid fa-circle-xmark text-red-500"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Search & Filters --}}
    <div class="card">
        <div class="p-4">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 relative">
                        <i class="fa-solid fa-search absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث بالاسم أو البريد...' : 'Search by name or email...' }}"
                               class="form-input {{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }}">
                    </div>
                    <select name="role" class="form-select w-auto flex-shrink-0">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'كل الأدوار' : 'All Roles' }}</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مدير' : 'Admin' }}</option>
                        <option value="branch_user" {{ request('role') == 'branch_user' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'موظف فرع' : 'Branch User' }}</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'عميل' : 'Customer' }}</option>
                    </select>
                    <select name="branch_id" class="form-select w-auto flex-shrink-0">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'كل الفروع' : 'All Branches' }}</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->display_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-shrink-0">
                            <i class="fa-solid fa-search"></i>
                            {{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}
                        </button>
                        @if(request()->hasAny(['search', 'role', 'branch_id']))
                            <a href="{{ route('users.index') }}" class="btn-secondary flex-shrink-0">
                                <i class="fa-solid fa-xmark"></i>
                                {{ app()->getLocale() === 'ar' ? 'مسح' : 'Clear' }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">#</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الدور' : 'Role' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الفرع' : 'Branch' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $roleColors = [
                                'admin'       => ['bg' => 'red',    'ar' => 'مدير',       'en' => 'Admin'],
                                'branch_user' => ['bg' => 'blue',   'ar' => 'موظف فرع',   'en' => 'Branch User'],
                                'customer'    => ['bg' => 'green',  'ar' => 'عميل',       'en' => 'Customer'],
                            ];
                            $userRole = $user->getRoleNames()->first();
                            $roleInfo = $roleColors[$userRole] ?? ['bg' => 'gray', 'ar' => $userRole, 'en' => $userRole];
                        @endphp
                        <tr>
                            <td class="text-gray-400 text-xs">{{ $user->id }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-{{ $roleInfo['bg'] }}-100 text-{{ $roleInfo['bg'] }}-700 flex items-center justify-center font-semibold text-sm flex-shrink-0">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        @if($user->id === auth()->id())
                                            <div class="text-xs text-blue-500">{{ app()->getLocale() === 'ar' ? '(أنت)' : '(You)' }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm text-gray-600">{{ $user->email }}</td>
                            <td>
                                @if($userRole)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $roleInfo['bg'] }}-100 text-{{ $roleInfo['bg'] }}-700">
                                        {{ app()->getLocale() === 'ar' ? $roleInfo['ar'] : $roleInfo['en'] }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="text-sm text-gray-600">
                                @if($user->branch)
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fa-solid fa-building text-xs text-gray-400"></i>
                                        {{ $user->branch->display_name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 {{ app()->getLocale() === 'ar' ? 'ml-1.5' : 'mr-1.5' }}"></span>
                                        {{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 {{ app()->getLocale() === 'ar' ? 'ml-1.5' : 'mr-1.5' }}"></span>
                                        {{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="btn-icon btn-secondary" title="{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                              onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد من حذف هذا المستخدم؟' : 'Are you sure you want to delete this user?' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn-icon btn-danger" title="{{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-gray-400">
                                <i class="fa-solid fa-users text-5xl mb-3 block"></i>
                                <p class="text-sm">{{ app()->getLocale() === 'ar' ? 'لا يوجد مستخدمون' : 'No users found' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    {{ app()->getLocale() === 'ar' ? 'عرض' : 'Showing' }}
                    {{ $users->firstItem() }}-{{ $users->lastItem() }}
                    {{ app()->getLocale() === 'ar' ? 'من' : 'of' }}
                    {{ $users->total() }}
                    {{ app()->getLocale() === 'ar' ? 'نتيجة' : 'results' }}
                </p>
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
