@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'العملاء' : 'Customers')
@section('page-title', app()->getLocale() === 'ar' ? 'العملاء' : 'Customers')

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
                <span class="text-gray-900 font-medium">{{ app()->getLocale() === 'ar' ? 'العملاء' : 'Customers' }}</span>
            </nav>
            <h2 class="text-xl font-bold text-gray-900">{{ app()->getLocale() === 'ar' ? 'قائمة العملاء' : 'Customer List' }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $customers->total() }} {{ app()->getLocale() === 'ar' ? 'نتيجة' : 'results' }}</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            {{ app()->getLocale() === 'ar' ? 'إضافة عميل' : 'Add Customer' }}
        </a>
    </div>

    {{-- Search --}}
    <div class="card">
        <div class="p-4">
            <form method="GET" action="{{ route('customers.index') }}">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 relative">
                        <i class="fa-solid fa-search absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث بالاسم أو الهاتف أو البريد...' : 'Search by name, phone or email...' }}"
                               class="form-input {{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }}">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-shrink-0">
                            <i class="fa-solid fa-search"></i>
                            {{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}
                        </button>
                        @if(request('search'))
                            <a href="{{ route('customers.index') }}" class="btn-secondary flex-shrink-0">
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
                            {{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الدولة' : 'Country' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'النوع' : 'Type' }}
                        </th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                            {{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td class="text-gray-400 text-xs">{{ $customer->id }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-sm flex-shrink-0">
                                        {{ mb_substr($customer->full_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $customer->full_name }}</div>
                                        @if($customer->email)
                                            <div class="text-xs text-gray-400">{{ $customer->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-gray-900">{{ $customer->phone }}</div>
                                @if($customer->phone_alt)
                                    <div class="text-xs text-gray-400">{{ $customer->phone_alt }}</div>
                                @endif
                            </td>
                            <td class="text-sm text-gray-600">{{ $customer->city ?: '—' }}</td>
                            <td class="text-sm text-gray-600">{{ $customer->country ?: '—' }}</td>
                            <td>
                                @php
                                    $typeLabels = [
                                        'sender'   => ['ar' => 'مرسل',    'en' => 'Sender',   'color' => 'blue'],
                                        'receiver' => ['ar' => 'مستلم',   'en' => 'Receiver', 'color' => 'green'],
                                        'both'     => ['ar' => 'كلاهما',  'en' => 'Both',     'color' => 'purple'],
                                    ];
                                    $typeInfo = $typeLabels[$customer->type] ?? ['ar' => $customer->type, 'en' => $customer->type, 'color' => 'gray'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    bg-{{ $typeInfo['color'] }}-100 text-{{ $typeInfo['color'] }}-700">
                                    {{ app()->getLocale() === 'ar' ? $typeInfo['ar'] : $typeInfo['en'] }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('customers.edit', $customer) }}"
                                       class="btn-icon btn-secondary" title="{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                          onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
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
                            <td colspan="7" class="text-center py-16 text-gray-400">
                                <i class="fa-solid fa-users-slash text-5xl mb-3 block"></i>
                                <p class="text-sm">{{ app()->getLocale() === 'ar' ? 'لا توجد نتائج' : 'No customers found' }}</p>
                                @if(request('search'))
                                    <a href="{{ route('customers.index') }}" class="text-blue-600 hover:underline text-xs mt-1 inline-block">
                                        {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'Show all' }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    {{ app()->getLocale() === 'ar' ? 'عرض' : 'Showing' }}
                    {{ $customers->firstItem() }}-{{ $customers->lastItem() }}
                    {{ app()->getLocale() === 'ar' ? 'من' : 'of' }}
                    {{ $customers->total() }}
                    {{ app()->getLocale() === 'ar' ? 'نتيجة' : 'results' }}
                </p>
                {{ $customers->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
