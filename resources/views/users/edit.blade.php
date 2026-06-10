@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تعديل المستخدم' : 'Edit User')
@section('page-title', app()->getLocale() === 'ar' ? 'تعديل المستخدم' : 'Edit User')

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
        <span class="text-gray-900 font-medium">{{ app()->getLocale() === 'ar' ? 'تعديل: ' : 'Edit: ' }}{{ $user->name }}</span>
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

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

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
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
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
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
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
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
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

                {{-- Change Password --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-lock text-orange-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'تغيير كلمة المرور' : 'Change Password' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-sm text-gray-500 mb-4">
                            {{ app()->getLocale() === 'ar' ? 'اتركه فارغاً إذا لم تريد تغيير كلمة المرور' : 'Leave blank to keep the current password' }}
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- New Password --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'كلمة المرور الجديدة' : 'New Password' }}
                                </label>
                                <input type="password" name="password"
                                       class="form-input @error('password') border-red-500 @enderror"
                                       minlength="8"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'على الأقل 8 أحرف' : 'At least 8 characters' }}"
                                       dir="ltr">
                                @error('password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm New Password --}}
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}
                                </label>
                                <input type="password" name="password_confirmation"
                                       class="form-input"
                                       minlength="8"
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
                                <option value="admin" {{ old('role', $currentRole) == 'admin' ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? 'مدير النظام' : 'Admin' }}
                                </option>
                                <option value="branch_user" {{ old('role', $currentRole) == 'branch_user' ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? 'موظف فرع' : 'Branch User' }}
                                </option>
                                <option value="customer" {{ old('role', $currentRole) == 'customer' ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? 'عميل' : 'Customer' }}
                                </option>
                            </select>
                            @error('role')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Locale --}}
                        <div>
                            <label class="form-label">
                                {{ app()->getLocale() === 'ar' ? 'لغة الواجهة' : 'Interface Language' }}
                            </label>
                            <select name="locale" class="form-select @error('locale') border-red-500 @enderror">
                                <option value="ar" {{ old('locale', $user->locale) == 'ar' ? 'selected' : '' }}>
                                    العربية
                                </option>
                                <option value="en" {{ old('locale', $user->locale) == 'en' ? 'selected' : '' }}>
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
                                       {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ app()->getLocale() === 'ar' ? 'الحساب نشط' : 'Account is Active' }}
                                </span>
                            </label>
                            @if($user->id === auth()->id())
                                <p class="text-xs text-amber-600 mt-1">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ app()->getLocale() === 'ar' ? 'هذا حسابك الحالي' : 'This is your current account' }}
                                </p>
                            @endif
                        </div>
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
                                <span>{{ app()->getLocale() === 'ar' ? 'رقم المستخدم' : 'User ID' }}</span>
                                <span class="font-mono font-medium text-gray-900">#{{ $user->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'تاريخ الإنشاء' : 'Created' }}</span>
                                <span>{{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'آخر تحديث' : 'Last Updated' }}</span>
                                <span>{{ $user->updated_at->format('d/m/Y') }}</span>
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
