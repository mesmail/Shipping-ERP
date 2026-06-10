@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تعديل السائق' : 'Edit Driver')
@section('page-title', app()->getLocale() === 'ar' ? 'تعديل السائق' : 'Edit Driver')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
            <i class="fa-solid fa-house text-xs"></i>
        </a>
        <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
        <a href="{{ route('drivers.index') }}" class="hover:text-blue-600">
            {{ app()->getLocale() === 'ar' ? 'السائقون' : 'Drivers' }}
        </a>
        <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
        <span class="text-gray-900 font-medium">{{ app()->getLocale() === 'ar' ? 'تعديل: ' : 'Edit: ' }}{{ $driver->name }}</span>
    </nav>

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

    <form method="POST" action="{{ route('drivers.update', $driver) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Main form --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Personal Information --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-blue-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'المعلومات الشخصية' : 'Personal Information' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Name --}}
                            <div class="sm:col-span-2">
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $driver->name) }}"
                                       class="form-input @error('name') border-red-500 @enderror"
                                       required placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل اسم السائق' : 'Enter driver name' }}">
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" value="{{ old('phone', $driver->phone) }}"
                                       class="form-input @error('phone') border-red-500 @enderror"
                                       required placeholder="+60xxxxxxxxx">
                                @error('phone')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alt Phone --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'هاتف بديل' : 'Alt Phone' }}
                                </label>
                                <input type="tel" name="phone_alt" value="{{ old('phone_alt', $driver->phone_alt) }}"
                                       class="form-input @error('phone_alt') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'اختياري' : 'Optional' }}">
                                @error('phone_alt')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ID Number --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الهوية' : 'ID Number' }}
                                </label>
                                <input type="text" name="id_number" value="{{ old('id_number', $driver->id_number) }}"
                                       class="form-input @error('id_number') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'رقم الهوية الوطنية' : 'National ID number' }}">
                                @error('id_number')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- License Number --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'رقم رخصة القيادة' : 'License Number' }}
                                </label>
                                <input type="text" name="license_number" value="{{ old('license_number', $driver->license_number) }}"
                                       class="form-input @error('license_number') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'رقم الرخصة' : 'License number' }}">
                                @error('license_number')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vehicle & Branch --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-car text-indigo-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'المركبة والفرع' : 'Vehicle & Branch' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Vehicle Number --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'رقم المركبة' : 'Vehicle Number' }}
                                </label>
                                <input type="text" name="vehicle_number" value="{{ old('vehicle_number', $driver->vehicle_number) }}"
                                       class="form-input font-mono @error('vehicle_number') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'لوحة ترقيم السيارة' : 'e.g. ABC 1234' }}">
                                @error('vehicle_number')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Branch --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'الفرع' : 'Branch' }}
                                </label>
                                <select name="branch_id" class="form-select @error('branch_id') border-red-500 @enderror">
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'بدون فرع' : 'No Branch' }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $driver->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-note-sticky text-yellow-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'ملاحظات' : 'Notes' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" rows="3"
                                  class="form-textarea w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 outline-none transition"
                                  placeholder="{{ app()->getLocale() === 'ar' ? 'أضف ملاحظات...' : 'Add notes...' }}">{{ old('notes', $driver->notes) }}</textarea>
                        @error('notes')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Status --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-toggle-on text-green-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'حالة السائق' : 'Driver Status' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   class="w-4 h-4 text-blue-600 rounded border-gray-300"
                                   {{ old('is_active', $driver->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">
                                {{ app()->getLocale() === 'ar' ? 'السائق نشط' : 'Driver is Active' }}
                            </span>
                        </label>
                        <p class="text-xs text-gray-400 mt-2">
                            {{ app()->getLocale() === 'ar' ? 'السائقون النشطون فقط يظهرون في قوائم الاختيار' : 'Only active drivers appear in selection lists' }}
                        </p>
                    </div>
                </div>

                {{-- Record info --}}
                <div class="card bg-gray-50">
                    <div class="card-body">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">
                            {{ app()->getLocale() === 'ar' ? 'معلومات السجل' : 'Record Info' }}
                        </h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'رقم السائق' : 'Driver ID' }}</span>
                                <span class="font-mono font-medium text-gray-900">#{{ $driver->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'تاريخ الإنشاء' : 'Created' }}</span>
                                <span>{{ $driver->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'إجمالي الشحنات' : 'Total Shipments' }}</span>
                                <span class="font-medium text-gray-900">{{ $driver->shipments()->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="card">
                    <div class="card-body space-y-3">
                        <button type="submit" class="btn-primary w-full justify-center btn-lg">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ app()->getLocale() === 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}
                        </button>
                        <a href="{{ route('drivers.index') }}" class="btn-secondary w-full justify-center">
                            <i class="fa-solid fa-xmark"></i>
                            {{ app()->getLocale() === 'ar' ? 'إلغاء' : 'Cancel' }}
                        </a>
                    </div>
                </div>

                {{-- Validation errors --}}
                @if($errors->any())
                    <div class="card border-red-200">
                        <div class="card-body">
                            <h4 class="text-sm font-semibold text-red-700 mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ app()->getLocale() === 'ar' ? 'يرجى تصحيح الأخطاء' : 'Please fix the errors' }}
                            </h4>
                            <ul class="text-xs text-red-600 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="flex items-start gap-1">
                                        <span>•</span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </form>
</div>
@endsection
