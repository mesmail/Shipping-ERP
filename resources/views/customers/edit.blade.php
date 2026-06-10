@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تعديل العميل' : 'Edit Customer')
@section('page-title', app()->getLocale() === 'ar' ? 'تعديل العميل' : 'Edit Customer')

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
        <span class="text-gray-900 font-medium">{{ app()->getLocale() === 'ar' ? 'تعديل: ' : 'Edit: ' }}{{ $customer->full_name }}</span>
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

    <form method="POST" action="{{ route('customers.update', $customer) }}">
        @csrf
        @method('PUT')

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
                                <input type="text" name="full_name" value="{{ old('full_name', $customer->full_name) }}"
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
                                <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}"
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
                                <input type="tel" name="phone_alt" value="{{ old('phone_alt', $customer->phone_alt) }}"
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
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}"
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
                                <input type="text" name="id_number" value="{{ old('id_number', $customer->id_number) }}"
                                       class="form-input @error('id_number') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'اختياري' : 'Optional' }}">
                                @error('id_number')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Location --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-red-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'معلومات العنوان' : 'Address Information' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- City --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}
                                </label>
                                <input type="text" name="city" value="{{ old('city', $customer->city) }}"
                                       class="form-input @error('city') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}">
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
                                    <option value="Malaysia" {{ old('country', $customer->country) == 'Malaysia' ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}
                                    </option>
                                    <option value="Yemen" {{ old('country', $customer->country) == 'Yemen' ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}
                                    </option>
                                </select>
                                @error('country')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="sm:col-span-2">
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'العنوان التفصيلي' : 'Address' }}
                                </label>
                                <input type="text" name="address" value="{{ old('address', $customer->address) }}"
                                       class="form-input @error('address') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل العنوان' : 'Enter address' }}">
                                @error('address')
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
                                  placeholder="{{ app()->getLocale() === 'ar' ? 'أضف ملاحظات...' : 'Add notes...' }}">{{ old('notes', $customer->notes) }}</textarea>
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
                            <option value="sender" {{ old('type', $customer->type) == 'sender' ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? 'مرسل' : 'Sender' }}
                            </option>
                            <option value="receiver" {{ old('type', $customer->type) == 'receiver' ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? 'مستلم' : 'Receiver' }}
                            </option>
                            <option value="both" {{ old('type', $customer->type) == 'both' ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? 'كلاهما' : 'Both' }}
                            </option>
                        </select>
                        @error('type')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Customer Info --}}
                <div class="card bg-gray-50">
                    <div class="card-body">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">
                            {{ app()->getLocale() === 'ar' ? 'معلومات السجل' : 'Record Info' }}
                        </h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'رقم العميل' : 'Customer ID' }}</span>
                                <span class="font-mono font-medium text-gray-900">#{{ $customer->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'تاريخ الإنشاء' : 'Created' }}</span>
                                <span>{{ $customer->created_at->format('d/m/Y') }}</span>
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
