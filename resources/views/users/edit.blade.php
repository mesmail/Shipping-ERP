@extends('layouts.app')
@section('title', app()->getLocale() === 'ar' ? 'تعديل المستخدم' : 'Edit User')
@section('page-title', app()->getLocale() === 'ar' ? 'تعديل المستخدم' : 'Edit User')
@section('content')
<div class="max-w-xl space-y-4">
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('users.index') }}" class="hover:text-blue-600">{{ __('app.users') }}</a>
        <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
        <span class="text-gray-900">{{ $user->name }}</span>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }} <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'كلمة المرور الجديدة' : 'New Password' }}</label>
                        <input type="password" name="password" class="form-input" minlength="8">
                        <p class="text-xs text-gray-400 mt-1">{{ app()->getLocale() === 'ar' ? 'اتركه فارغاً إذا لم تريد التغيير' : 'Leave blank to keep unchanged' }}</p>
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                        <input type="password" name="password_confirmation" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الدور' : 'Role' }} <span class="text-red-500">*</span></label>
                        <select name="role" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اللغة' : 'Language' }}</label>
                        <select name="locale" class="form-select">
                            <option value="ar" {{ $user->locale == 'ar' ? 'selected' : '' }}>العربية</option>
                            <option value="en" {{ $user->locale == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">{{ __('app.branches') }}</label>
                        <select name="branch_id" class="form-select">
                            <option value="">{{ app()->getLocale() === 'ar' ? 'بدون فرع' : 'No Branch' }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $user->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                            <span class="text-sm text-gray-700">{{ app()->getLocale() === 'ar' ? 'حساب نشط' : 'Active Account' }}</span>
                        </label>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> {{ __('app.actions.save') }}</button>
                    <a href="{{ route('users.index') }}" class="btn-secondary">{{ __('app.actions.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
