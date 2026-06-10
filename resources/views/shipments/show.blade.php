@extends('layouts.app')

@section('title', $shipment->tracking_number)
@section('page-title', __('shipments.show'))

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('shipments.index') }}" class="btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
            </a>
            <div>
                <h2 class="font-mono font-bold text-gray-900 text-lg">{{ $shipment->tracking_number }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    @include('shipments._status_badge', ['status' => $shipment->status])
                    <span class="text-xs text-gray-400">{{ $shipment->shipment_date->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('shipments.label', $shipment) }}" target="_blank" class="btn-secondary btn-sm">
                <i class="fa-solid fa-print"></i>
                {{ __('shipments.label.print') }}
            </a>
            <a href="{{ route('shipments.label.pdf', $shipment) }}" class="btn-secondary btn-sm">
                <i class="fa-solid fa-file-pdf"></i>
                {{ __('shipments.label.download') }}
            </a>
            <a href="{{ route('shipments.edit', $shipment) }}" class="btn-primary btn-sm">
                <i class="fa-solid fa-pen"></i>
                {{ __('app.actions.edit') }}
            </a>
        </div>
    </div>

    {{-- Status progress bar --}}
    @php
        $statusOrder = ['created','collected','in_transit','transferred','arrived_at_branch','out_for_delivery','delivered'];
        $currentIdx = array_search($shipment->status, $statusOrder);
    @endphp
    @if(!in_array($shipment->status, ['failed_delivery','returned']))
    <div class="card p-4">
        <div class="flex items-center justify-between">
            @foreach($statusOrder as $idx => $st)
                <div class="flex flex-col items-center flex-1 {{ !$loop->last ? 'relative' : '' }}">
                    @if(!$loop->last)
                        <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} right-0 h-0.5 {{ $idx < $currentIdx ? 'bg-blue-500' : 'bg-gray-200' }}" style="width:calc(100% - 2rem); {{ app()->getLocale() === 'ar' ? 'right:1rem' : 'left:1rem' }}"></div>
                    @endif
                    <div class="w-8 h-8 rounded-full flex items-center justify-center z-10 relative
                        {{ $idx < $currentIdx ? 'bg-blue-600 text-white' : ($idx == $currentIdx ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 'bg-gray-200 text-gray-400') }}">
                        @if($idx < $currentIdx)
                            <i class="fa-solid fa-check text-xs"></i>
                        @else
                            <span class="text-xs font-bold">{{ $idx + 1 }}</span>
                        @endif
                    </div>
                    <span class="text-xs mt-1.5 text-center hidden sm:block
                        {{ $idx <= $currentIdx ? 'text-blue-700 font-medium' : 'text-gray-400' }}">
                        {{ __('shipments.status.'.$st) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Main info --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Shipment Info --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">{{ app()->getLocale() === 'ar' ? 'معلومات الشحنة' : 'Shipment Details' }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <div class="text-gray-500 text-xs mb-1">{{ __('shipments.tracking_number') }}</div>
                            <div class="font-mono font-bold text-blue-700">{{ $shipment->tracking_number }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 text-xs mb-1">{{ __('shipments.shipment_type') }}</div>
                            <div class="font-medium">{{ __('shipments.type.'.$shipment->shipment_type) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 text-xs mb-1">{{ __('shipments.delivery_type') }}</div>
                            <div class="font-medium">{{ __('shipments.delivery_types.'.$shipment->delivery_type) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 text-xs mb-1">{{ __('shipments.origin_branch') }}</div>
                            <div class="font-medium">{{ $shipment->originBranch?->display_name }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 text-xs mb-1">{{ __('shipments.destination_branch') }}</div>
                            <div class="font-medium">{{ $shipment->destinationBranch?->display_name }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 text-xs mb-1">{{ __('shipments.current_branch') }}</div>
                            <div class="font-medium">{{ $shipment->currentBranch?->display_name }}</div>
                        </div>
                        @if($shipment->driver)
                        <div>
                            <div class="text-gray-500 text-xs mb-1">{{ __('shipments.assigned_driver') }}</div>
                            <div class="font-medium">{{ $shipment->driver->name }}</div>
                        </div>
                        @endif
                        @if($shipment->notes)
                        <div class="col-span-2 sm:col-span-3">
                            <div class="text-gray-500 text-xs mb-1">{{ __('shipments.notes') }}</div>
                            <div class="text-gray-700">{{ $shipment->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sender / Receiver --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user text-blue-500"></i>
                            {{ __('shipments.sender.title') }}
                        </h3>
                    </div>
                    <div class="card-body space-y-2 text-sm">
                        <div class="font-bold text-gray-900">{{ $shipment->sender_name }}</div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fa-solid fa-phone text-blue-500 w-4"></i>
                            {{ $shipment->sender_phone }}
                        </div>
                        @if($shipment->sender_phone_alt)
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fa-solid fa-phone-flip text-blue-400 w-4"></i>
                            {{ $shipment->sender_phone_alt }}
                        </div>
                        @endif
                        @if($shipment->sender_email)
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fa-solid fa-envelope text-blue-400 w-4"></i>
                            {{ $shipment->sender_email }}
                        </div>
                        @endif
                        @if($shipment->sender_address)
                        <div class="flex items-start gap-2 text-gray-600">
                            <i class="fa-solid fa-location-dot text-blue-400 w-4 mt-0.5"></i>
                            <span>{{ $shipment->sender_address }}, {{ $shipment->sender_city }}, {{ $shipment->sender_country }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user-check text-green-500"></i>
                            {{ __('shipments.receiver.title') }}
                        </h3>
                    </div>
                    <div class="card-body space-y-2 text-sm">
                        <div class="font-bold text-gray-900">{{ $shipment->receiver_name }}</div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fa-solid fa-phone text-green-500 w-4"></i>
                            {{ $shipment->receiver_phone }}
                        </div>
                        @if($shipment->receiver_phone_alt)
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fa-solid fa-phone-flip text-green-400 w-4"></i>
                            {{ $shipment->receiver_phone_alt }}
                        </div>
                        @endif
                        @if($shipment->receiver_address)
                        <div class="flex items-start gap-2 text-gray-600">
                            <i class="fa-solid fa-location-dot text-green-400 w-4 mt-0.5"></i>
                            <span>{{ $shipment->receiver_address }}, {{ $shipment->receiver_city }}, {{ $shipment->receiver_country }}</span>
                        </div>
                        @endif
                        @if($shipment->delivery_instructions)
                        <div class="mt-2 p-2 bg-amber-50 rounded text-amber-800 text-xs">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            {{ $shipment->delivery_instructions }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Packages --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-purple-500"></i>
                        {{ __('shipments.packages.title') }}
                        <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $shipment->total_packages }}</span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table w-full">
                        <thead>
                            <tr>
                                <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">#</th>
                                <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.packages.description') }}</th>
                                <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ app()->getLocale() === 'ar' ? 'الأبعاد (سم)' : 'Dimensions (cm)' }}</th>
                                <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.packages.actual_weight') }}</th>
                                <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.packages.volumetric_weight') }}</th>
                                <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.packages.chargeable_weight') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipment->packages as $pkg)
                                <tr>
                                    <td class="font-semibold">{{ $loop->iteration }}</td>
                                    <td>{{ $pkg->description ?: '-' }}</td>
                                    <td class="font-mono text-xs">{{ $pkg->length }}×{{ $pkg->width }}×{{ $pkg->height }}</td>
                                    <td>{{ number_format($pkg->actual_weight, 3) }} kg</td>
                                    <td>{{ number_format($pkg->volumetric_weight, 3) }} kg</td>
                                    <td class="font-semibold text-blue-700">{{ number_format($pkg->chargeable_weight, 3) }} kg</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-blue-50 font-semibold">
                                <td colspan="3" class="px-4 py-3 text-sm">{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($shipment->total_actual_weight, 3) }} kg</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($shipment->total_volumetric_weight, 3) }} kg</td>
                                <td class="px-4 py-3 text-sm text-blue-700">{{ number_format($shipment->total_chargeable_weight, 3) }} kg</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-6">

            {{-- Update Status --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 text-sm">{{ __('shipments.update_status') }}</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('shipments.update-status', $shipment) }}">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-3">
                            <div>
                                <label class="form-label text-xs">{{ __('shipments.filter_status') }}</label>
                                <select name="status" class="form-select text-sm">
                                    @foreach(\App\Models\Shipment::$statuses as $st)
                                        <option value="{{ $st }}" {{ $shipment->status == $st ? 'selected' : '' }}>
                                            {{ __('shipments.status.'.$st) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-xs">{{ __('shipments.notes') }}</label>
                                <textarea name="notes" rows="2" class="form-textarea text-sm"
                                          placeholder="{{ app()->getLocale() === 'ar' ? 'ملاحظات اختيارية...' : 'Optional notes...' }}"></textarea>
                            </div>
                            <button type="submit" class="btn-primary w-full justify-center text-sm">
                                <i class="fa-solid fa-arrow-rotate-right"></i>
                                {{ __('shipments.update_status') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Barcodes --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 text-sm">{{ app()->getLocale() === 'ar' ? 'الباركود وكود QR' : 'Barcode & QR Code' }}</h3>
                </div>
                <div class="card-body text-center space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Barcode</p>
                        <img src="{{ route('shipments.barcode', $shipment) }}" alt="Barcode" class="mx-auto max-w-full">
                    </div>
                    <div class="border-t pt-3">
                        <p class="text-xs text-gray-500 mb-2">QR Code</p>
                        <img src="{{ route('shipments.qrcode', $shipment) }}" alt="QR Code" class="mx-auto w-32 h-32">
                    </div>
                    <p class="font-mono text-xs text-gray-500">{{ $shipment->tracking_number }}</p>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-timeline text-blue-500"></i>
                        {{ __('shipments.timeline.title') }}
                    </h3>
                </div>
                <div class="card-body">
                    @forelse($shipment->events as $event)
                        <div class="flex gap-3 {{ !$loop->last ? 'pb-4 border-b border-gray-100 mb-4' : '' }}">
                            <div class="flex-shrink-0 mt-1">
                                @php
                                    $eventColors = [
                                        'created'=>'bg-blue-100 text-blue-600',
                                        'collected'=>'bg-indigo-100 text-indigo-600',
                                        'in_transit'=>'bg-yellow-100 text-yellow-700',
                                        'transferred'=>'bg-purple-100 text-purple-600',
                                        'arrived_at_branch'=>'bg-cyan-100 text-cyan-600',
                                        'out_for_delivery'=>'bg-orange-100 text-orange-700',
                                        'delivered'=>'bg-green-100 text-green-600',
                                        'received_closed'=>'bg-emerald-100 text-emerald-600',
                                        'failed_delivery'=>'bg-red-100 text-red-600',
                                        'returned'=>'bg-gray-100 text-gray-600',
                                    ];
                                    $ec = $eventColors[$event->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <div class="w-8 h-8 rounded-full {{ $ec }} flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-circle-dot"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="font-semibold text-sm text-gray-900">{{ __('shipments.status.'.$event->status) }}</div>
                                    <div class="text-xs text-gray-400 whitespace-nowrap">{{ $event->event_at->format('d/m H:i') }}</div>
                                </div>
                                @if($event->branch)
                                    <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                        <i class="fa-solid fa-building text-xs"></i>
                                        {{ $event->branch->display_name }}
                                    </div>
                                @endif
                                @if($event->user)
                                    <div class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class="fa-solid fa-user text-xs"></i>
                                        {{ $event->user->name }}
                                    </div>
                                @endif
                                @if($event->notes)
                                    <div class="mt-1 text-xs text-gray-600 bg-gray-50 rounded p-1.5">{{ $event->notes }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">{{ __('app.messages.no_results') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
