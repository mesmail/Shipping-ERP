@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'إضافة مستخدم' : 'Add User')
@section('page-title', app()->getLocale() === 'ar' ? 'إضافة مستخدم' : 'Add User')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
            <i class="fa-solid fa-house text-xs"></i>
        </a>
        <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
        <a href="{{ route('users.index') }}" class="hover:text-blue-600">
            {{ app()->getLocale() === 'ar' ? 'المستخدمون' : 'Users' }}
        </a>
        <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
        <span class="text-gray-900 font-medium">{{ app()->getLocale() === 'ar' ? 'إضافة مستخدم' : 'Add User' }}</span>
    </nav>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Main form --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Account Information --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user text-blue-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'معلومات الحساب' : 'Account Information' }}
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
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="form-input @error('name') border-red-500 @enderror"
                                       required placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل الاسم الكامل' : 'Enter full name' }}">
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="form-input @error('email') border-red-500 @enderror"
                                       required placeholder="user@example.com"
                                       dir="ltr">
                                @error('email')
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
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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

                {{-- Password --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-lock text-orange-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Password --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password"
                                       class="form-input @error('password') border-red-500 @enderror"
                                       required minlength="8"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'على الأقل 8 أحرف' : 'At least 8 characters' }}"
                                       dir="ltr">
                                @error('password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation"
                                       class="form-input"
                                       required minlength="8"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أعد كلمة المرور' : 'Repeat password' }}"
                                       dir="ltr">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Role & Settings --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-purple-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'الصلاحيات' : 'Permissions' }}
                        </h3>
                    </div>
                    <div class="card-body space-y-4">

                        {{-- Role --}}
                        <div>
                            <label class="form-label">
                                {{ app()->getLocale() === 'ar' ? 'الدور' : 'Role' }}
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="role" class="form-select @error('role') border-red-500 @enderror" required>
                                <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الدور' : 'Select Role' }}</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? 'مدير النظام' : 'Admin' }}
                                </option>
                                <option value="branch_user" {{ old('role') == 'branch_user' ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? 'موظف فرع' : 'Branch User' }}
                                </option>
                                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? 'عميل' : 'Customer' }}
                                </option>
                            </select>
                            @error('role')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 space-y-1.5 text-xs text-gray-500">
                                <div class="flex items-start gap-1.5">
                                    <span class="inline-block w-2 h-2 rounded-full bg-red-400 mt-0.5 flex-shrink-0"></span>
                                    <span>{{ app()->getLocale() === 'ar' ? 'مدير: وصول كامل لكل الوظائف' : 'Admin: Full access to all features' }}</span>
                                </div>
                                <div class="flex items-start gap-1.5">
                                    <span class="inline-block w-2 h-2 rounded-full bg-blue-400 mt-0.5 flex-shrink-0"></span>
                                    <span>{{ app()->getLocale() === 'ar' ? 'موظف فرع: إدارة شحنات الفرع' : 'Branch User: Manage branch shipments' }}</span>
                                </div>
                                <div class="flex items-start gap-1.5">
                                    <span class="inline-block w-2 h-2 rounded-full bg-green-400 mt-0.5 flex-shrink-0"></span>
                                    <span>{{ app()->getLocale() === 'ar' ? 'عميل: عرض وتتبع شحناته فقط' : 'Customer: View and track own shipments' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Locale --}}
                        <div>
                            <label class="form-label">
                                {{ app()->getLocale() === 'ar' ? 'لغة الواجهة' : 'Interface Language' }}
                            </label>
                            <select name="locale" class="form-select @error('locale') border-red-500 @enderror">
                                <option value="ar" {{ old('locale', 'ar') == 'ar' ? 'selected' : '' }}>
                                    العربية
                                </option>
                                <option value="en" {{ old('locale') == 'en' ? 'selected' : '' }}>
                                    English
                                </option>
                            </select>
                            @error('locale')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- is_active --}}
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                       class="w-4 h-4 text-blue-600 rounded border-gray-300"
                                       {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ app()->getLocale() === 'ar' ? 'الحساب نشط' : 'Account is Active' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="card">
                    <div class="card-body space-y-3">
                        <button type="submit" class="btn-primary w-full justify-center btn-lg">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ app()->getLocale() === 'ar' ? 'إنشاء الحساب' : 'Create Account' }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn-secondary w-full justify-center">
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
