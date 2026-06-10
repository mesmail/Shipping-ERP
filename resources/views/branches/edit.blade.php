@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تعديل الفرع' : 'Edit Branch')
@section('page-title', app()->getLocale() === 'ar' ? 'تعديل الفرع' : 'Edit Branch')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
            <i class="fa-solid fa-house text-xs"></i>
        </a>
        <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
        <a href="{{ route('branches.index') }}" class="hover:text-blue-600">
            {{ app()->getLocale() === 'ar' ? 'الفروع' : 'Branches' }}
        </a>
        <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
        <span class="text-gray-900 font-medium">{{ app()->getLocale() === 'ar' ? 'تعديل: ' : 'Edit: ' }}{{ $branch->name }}</span>
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

    <form method="POST" action="{{ route('branches.update', $branch) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Main form --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Branch Names --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-building text-blue-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'معلومات الفرع' : 'Branch Information' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Name (English) --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'الاسم (إنجليزي)' : 'Name (English)' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $branch->name) }}"
                                       class="form-input @error('name') border-red-500 @enderror"
                                       required placeholder="e.g. Kuala Lumpur Branch"
                                       dir="ltr">
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Name (Arabic) --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'الاسم (عربي)' : 'Name (Arabic)' }}
                                </label>
                                <input type="text" name="name_ar" value="{{ old('name_ar', $branch->name_ar) }}"
                                       class="form-input @error('name_ar') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: فرع كوالالمبور' : 'e.g. فرع كوالالمبور' }}"
                                       dir="rtl">
                                @error('name_ar')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Code --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'رمز الفرع' : 'Branch Code' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="code" value="{{ old('code', $branch->code) }}"
                                       class="form-input font-mono uppercase @error('code') border-red-500 @enderror"
                                       required placeholder="e.g. KUL" maxlength="10" minlength="2"
                                       dir="ltr" oninput="this.value = this.value.toUpperCase()">
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ app()->getLocale() === 'ar' ? '2-10 أحرف، فريد لكل فرع' : '2-10 characters, unique per branch' }}
                                </p>
                                @error('code')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Country --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'الدولة' : 'Country' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="country" class="form-select @error('country') border-red-500 @enderror" required>
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الدولة' : 'Select Country' }}</option>
                                    <option value="Malaysia" {{ old('country', $branch->country) == 'Malaysia' ? 'selected' : '' }}>
                                        🇲🇾 {{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}
                                    </option>
                                    <option value="Yemen" {{ old('country', $branch->country) == 'Yemen' ? 'selected' : '' }}>
                                        🇾🇪 {{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}
                                    </option>
                                </select>
                                @error('country')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- City (English) --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'المدينة (إنجليزي)' : 'City (English)' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="city" value="{{ old('city', $branch->city) }}"
                                       class="form-input @error('city') border-red-500 @enderror"
                                       required placeholder="e.g. Kuala Lumpur"
                                       dir="ltr">
                                @error('city')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- City (Arabic) --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'المدينة (عربي)' : 'City (Arabic)' }}
                                </label>
                                <input type="text" name="city_ar" value="{{ old('city_ar', $branch->city_ar) }}"
                                       class="form-input @error('city_ar') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: كوالالمبور' : 'e.g. كوالالمبور' }}"
                                       dir="rtl">
                                @error('city_ar')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-phone text-green-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'معلومات التواصل' : 'Contact Information' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Phone --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }}
                                </label>
                                <input type="tel" name="phone" value="{{ old('phone', $branch->phone) }}"
                                       class="form-input @error('phone') border-red-500 @enderror"
                                       placeholder="+60xxxxxxxxx">
                                @error('phone')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}
                                </label>
                                <input type="email" name="email" value="{{ old('email', $branch->email) }}"
                                       class="form-input @error('email') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'اختياري' : 'Optional' }}">
                                @error('email')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="sm:col-span-2">
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }}
                                </label>
                                <input type="text" name="address" value="{{ old('address', $branch->address) }}"
                                       class="form-input @error('address') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'عنوان الفرع' : 'Branch address' }}">
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
                                  placeholder="{{ app()->getLocale() === 'ar' ? 'أضف ملاحظات...' : 'Add notes...' }}">{{ old('notes', $branch->notes) }}</textarea>
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
                            {{ app()->getLocale() === 'ar' ? 'حالة الفرع' : 'Branch Status' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   class="w-4 h-4 text-blue-600 rounded border-gray-300"
                                   {{ old('is_active', $branch->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">
                                {{ app()->getLocale() === 'ar' ? 'الفرع نشط' : 'Branch is Active' }}
                            </span>
                        </label>
                        <p class="text-xs text-gray-400 mt-2">
                            {{ app()->getLocale() === 'ar' ? 'الفروع النشطة فقط تظهر في قوائم الاختيار' : 'Only active branches appear in selection lists' }}
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
                                <span>{{ app()->getLocale() === 'ar' ? 'رقم الفرع' : 'Branch ID' }}</span>
                                <span class="font-mono font-medium text-gray-900">#{{ $branch->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'تاريخ الإنشاء' : 'Created' }}</span>
                                <span>{{ $branch->created_at->format('d/m/Y') }}</span>
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
                        <a href="{{ route('branches.index') }}" class="btn-secondary w-full justify-center">
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
