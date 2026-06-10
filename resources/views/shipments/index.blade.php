@extends('layouts.app')

@section('title', __('shipments.title'))
@section('page-title', __('shipments.title'))

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ __('shipments.plural') }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $shipments->total() }} {{ __('app.table.results') }}</p>
        </div>
        <a href="{{ route('shipments.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            {{ __('shipments.new') }}
        </a>
    </div>

    {{-- Filters --}}
    <div class="card">
        <div class="p-4">
            <form method="GET" action="{{ route('shipments.index') }}" x-data="{ filtersOpen: {{ request()->hasAny(['status','branch_id','driver_id','date_from','date_to']) ? 'true' : 'false' }} }">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 relative">
                        <i class="fa-solid fa-search absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('shipments.search_placeholder') }}"
                               class="form-input {{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }}">
                    </div>
                    <button type="button" @click="filtersOpen = !filtersOpen"
                            class="btn-secondary flex-shrink-0">
                        <i class="fa-solid fa-sliders"></i>
                        {{ __('app.actions.filter') }}
                        @if(request()->hasAny(['status','branch_id','driver_id','date_from','date_to']))
                            <span class="bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">!</span>
                        @endif
                    </button>
                    <button type="submit" class="btn-primary flex-shrink-0">
                        <i class="fa-solid fa-search"></i>
                        {{ __('app.actions.search') }}
                    </button>
                </div>

                <div x-show="filtersOpen" x-transition class="mt-3 pt-3 border-t border-gray-100">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                        <div>
                            <label class="form-label text-xs">{{ __('shipments.filter_status') }}</label>
                            <select name="status" class="form-select text-sm">
                                <option value="">{{ __('app.messages.no_results') === __('app.messages.no_results') ? 'الكل' : 'All' }}</option>
                                @foreach(\App\Models\Shipment::$statuses as $s)
                                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                        {{ __('shipments.status.'.$s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-xs">{{ __('shipments.filter_branch') }}</label>
                            <select name="branch_id" class="form-select text-sm">
                                <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-xs">{{ __('shipments.filter_driver') }}</label>
                            <select name="driver_id" class="form-select text-sm">
                                <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-xs">{{ __('shipments.filter_from') }}</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input text-sm">
                        </div>
                        <div>
                            <label class="form-label text-xs">{{ __('shipments.filter_to') }}</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input text-sm">
                        </div>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button type="submit" class="btn-primary btn-sm">
                            <i class="fa-solid fa-filter"></i>
                            {{ __('app.actions.filter') }}
                        </button>
                        <a href="{{ route('shipments.index') }}" class="btn-secondary btn-sm">
                            <i class="fa-solid fa-xmark"></i>
                            {{ __('app.actions.reset') }}
                        </a>
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
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.tracking_number') }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.sender.title') }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.receiver.title') }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.destination_branch') }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.packages.total_packages') }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.filter_status') }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.shipment_date') }}</th>
                        <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('app.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $shipment)
                        <tr class="cursor-pointer" onclick="window.location='{{ route('shipments.show', $shipment) }}'">
                            <td>
                                <div class="font-mono font-semibold text-blue-700 text-xs">{{ $shipment->tracking_number }}</div>
                                <div class="text-xs text-gray-400">{{ $shipment->shipment_type_label ?? __('shipments.type.'.$shipment->shipment_type) }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $shipment->sender_name }}</div>
                                <div class="text-xs text-gray-500">{{ $shipment->sender_phone }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $shipment->receiver_name }}</div>
                                <div class="text-xs text-gray-500">{{ $shipment->receiver_phone }}</div>
                            </td>
                            <td>
                                <div class="text-sm">{{ $shipment->destinationBranch?->display_name }}</div>
                                <div class="text-xs text-gray-400">{{ $shipment->destinationBranch?->city }}</div>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm">
                                    {{ $shipment->total_packages }}
                                </span>
                            </td>
                            <td>
                                @include('shipments._status_badge', ['status' => $shipment->status])
                            </td>
                            <td class="text-gray-500 text-xs">{{ $shipment->shipment_date->format('d/m/Y') }}</td>
                            <td onclick="event.stopPropagation()">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('shipments.show', $shipment) }}"
                                       class="btn-icon btn-secondary" title="{{ __('app.actions.view') }}">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('shipments.edit', $shipment) }}"
                                       class="btn-icon btn-secondary" title="{{ __('app.actions.edit') }}">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    <a href="{{ route('shipments.label', $shipment) }}" target="_blank"
                                       class="btn-icon btn-secondary" title="{{ __('shipments.label.print') }}">
                                        <i class="fa-solid fa-print text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-16 text-gray-400">
                                <i class="fa-solid fa-box-open text-5xl mb-3 block"></i>
                                <p class="text-sm">{{ __('app.messages.no_results') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shipments->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    {{ __('app.table.showing') }} {{ $shipments->firstItem() }}-{{ $shipments->lastItem() }}
                    {{ __('app.table.of') }} {{ $shipments->total() }} {{ __('app.table.results') }}
                </p>
                {{ $shipments->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
