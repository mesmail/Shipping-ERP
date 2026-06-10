@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'الفروع' : 'Branches')
@section('page-title', app()->getLocale() === 'ar' ? 'الفروع' : 'Branches')

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
                <span class="text-gray-900 font-medium">{{ app()->getLocale() === 'ar' ? 'الفروع' : 'Branches' }}</span>
            </nav>
            <h2 class="text-xl font-bold text-gray-900">{{ app()->getLocale() === 'ar' ? 'قائمة الفروع' : 'Branch List' }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $branches->total() }} {{ app()->getLocale() === 'ar' ? 'نتيجة' : 'results' }}</p>
        </div>
        <a href="{{ route('branches.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            {{ app()->getLocale() === 'ar' ? 'إضافة فرع' : 'Add Branch' }}
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

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">#</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'اسم الفرع' : 'Branch Name' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الرمز' : 'Code' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الدولة' : 'Country' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}
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
                    @forelse($branches as $branch)
                        <tr>
                            <td class="text-gray-400 text-xs">{{ $branch->id }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($branch->code, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ app()->getLocale() === 'ar' && $branch->name_ar ? $branch->name_ar : $branch->name }}
                                        </div>
                                        @if(app()->getLocale() === 'ar' && $branch->name_ar && $branch->name)
                                            <div class="text-xs text-gray-400">{{ $branch->name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2 py-0.5 rounded font-mono text-xs font-semibold bg-gray-100 text-gray-700">
                                    {{ $branch->code }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-600">
                                {{ app()->getLocale() === 'ar' && $branch->city_ar ? $branch->city_ar : $branch->city }}
                            </td>
                            <td class="text-sm text-gray-600">
                                @if($branch->country === 'Malaysia')
                                    <span class="inline-flex items-center gap-1">
                                        <span>🇲🇾</span>
                                        <span>{{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}</span>
                                    </span>
                                @elseif($branch->country === 'Yemen')
                                    <span class="inline-flex items-center gap-1">
                                        <span>🇾🇪</span>
                                        <span>{{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}</span>
                                    </span>
                                @else
                                    {{ $branch->country ?: '—' }}
                                @endif
                            </td>
                            <td class="text-sm text-gray-600">{{ $branch->phone ?: '—' }}</td>
                            <td>
                                @if($branch->is_active)
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
                                    <a href="{{ route('branches.edit', $branch) }}"
                                       class="btn-icon btn-secondary" title="{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    <form method="POST" action="{{ route('branches.destroy', $branch) }}"
                                          onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد من حذف هذا الفرع؟' : 'Are you sure you want to delete this branch?' }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn-icon btn-danger" title="{{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-16 text-gray-400">
                                <i class="fa-solid fa-building text-5xl mb-3 block"></i>
                                <p class="text-sm">{{ app()->getLocale() === 'ar' ? 'لا توجد فروع بعد' : 'No branches yet' }}</p>
                                <a href="{{ route('branches.create') }}" class="text-blue-600 hover:underline text-xs mt-1 inline-block">
                                    {{ app()->getLocale() === 'ar' ? 'إضافة أول فرع' : 'Add your first branch' }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($branches->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    {{ app()->getLocale() === 'ar' ? 'عرض' : 'Showing' }}
                    {{ $branches->firstItem() }}-{{ $branches->lastItem() }}
                    {{ app()->getLocale() === 'ar' ? 'من' : 'of' }}
                    {{ $branches->total() }}
                    {{ app()->getLocale() === 'ar' ? 'نتيجة' : 'results' }}
                </p>
                {{ $branches->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
