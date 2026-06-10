@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'إضافة عميل' : 'Add Customer')
@section('page-title', app()->getLocale() === 'ar' ? 'إضافة عميل' : 'Add Customer')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
            <i class="fa-solid fa-house text-xs"></i>
        </a>
        <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
        <a href="{{ route('customers.index') }}" class="hover:text-blue-600">
            {{ app()->getLocale() === 'ar' ? 'العملاء' : 'Customers' }}
        </a>
        <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
        <span class="text-gray-900 font-medium">{{ app()->getLocale() === 'ar' ? 'إضافة عميل' : 'Add Customer' }}</span>
    </nav>

    <form method="POST" action="{{ route('customers.store') }}">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Main form --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Basic Information --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user text-blue-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'المعلومات الأساسية' : 'Basic Information' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Full Name --}}
                            <div class="sm:col-span-2">
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="full_name" value="{{ old('full_name') }}"
                                       class="form-input @error('full_name') border-red-500 @enderror"
                                       required placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل الاسم الكامل' : 'Enter full name' }}">
                                @error('full_name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
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
                                <input type="tel" name="phone_alt" value="{{ old('phone_alt') }}"
                                       class="form-input @error('phone_alt') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'اختياري' : 'Optional' }}">
                                @error('phone_alt')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="form-input @error('email') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'اختياري' : 'Optional' }}">
                                @error('email')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ID Number --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الهوية' : 'ID Number' }}
                                </label>
                                <input type="text" name="id_number" value="{{ old('id_number') }}"
                                       class="form-input @error('id_number') border-red-500 @enderror">
                                @error('id_number')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Address Information --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-red-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'معلومات العنوان' : 'Address Information' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Address --}}
                            <div class="sm:col-span-2">
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }}
                                </label>
                                <input type="text" name="address" value="{{ old('address') }}"
                                       class="form-input @error('address') border-red-500 @enderror">
                                @error('address')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}
                                </label>
                                <input type="text" name="city" value="{{ old('city') }}"
                                       class="form-input @error('city') border-red-500 @enderror">
                                @error('city')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Country --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'الدولة' : 'Country' }}
                                </label>
                                <select name="country" class="form-select @error('country') border-red-500 @enderror">
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الدولة' : 'Select Country' }}</option>
                                    <option value="Malaysia" {{ old('country') == 'Malaysia' ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}
                                    </option>
                                    <option value="Yemen" {{ old('country') == 'Yemen' ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}
                                    </option>
                                </select>
                                @error('country')
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
                                  placeholder="{{ app()->getLocale() === 'ar' ? 'أضف ملاحظات...' : 'Add notes...' }}">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Customer Type --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-tag text-purple-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'نوع العميل' : 'Customer Type' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <label class="form-label">
                            {{ app()->getLocale() === 'ar' ? 'النوع' : 'Type' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="type" class="form-select @error('type') border-red-500 @enderror" required>
                            <option value="sender" {{ old('type', 'sender') == 'sender' ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? 'مرسل' : 'Sender' }}
                            </option>
                            <option value="receiver" {{ old('type') == 'receiver' ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? 'مستلم' : 'Receiver' }}
                            </option>
                            <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? 'كلاهما' : 'Both' }}
                            </option>
                        </select>
                        @error('type')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="card">
                    <div class="card-body space-y-3">
                        <button type="submit" class="btn-primary w-full justify-center btn-lg">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ app()->getLocale() === 'ar' ? 'حفظ العميل' : 'Save Customer' }}
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn-secondary w-full justify-center">
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
