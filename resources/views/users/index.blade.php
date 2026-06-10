@extends('layouts.app')
@section('title', __('app.users'))
@section('page-title', __('app.users'))
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">{{ __('app.users') }}</h2>
        <a href="{{ route('users.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            {{ app()->getLocale() === 'ar' ? 'مستخدم جديد' : 'New User' }}
        </a>
    </div>
    <div class="card">
        <div class="p-4">
            <form method="GET">
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="{{ app()->getLocale() === 'ar' ? 'بحث...' : 'Search...' }}"
                           class="form-input flex-1 text-sm">
                    <button type="submit" class="btn-primary btn-sm"><i class="fa-solid fa-search"></i></button>
                    <a href="{{ route('users.index') }}" class="btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i></a>
                </div>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ app()->getLocale() === 'ar' ? 'الدور' : 'Role' }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('app.branches') }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('app.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold">{{ substr($user->name,0,1) }}</div>
                                    <span class="font-medium">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="text-gray-500 text-sm">{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-sm">{{ $user->branch?->display_name ?? '-' }}</td>
                            <td>
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>{{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('users.edit', $user) }}" class="btn-icon btn-secondary" title="{{ __('app.actions.edit') }}">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-danger"><i class="fa-solid fa-trash text-sm"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-12 text-gray-400"><i class="fa-solid fa-users text-4xl mb-2 block"></i>{{ __('app.messages.no_results') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $users->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
