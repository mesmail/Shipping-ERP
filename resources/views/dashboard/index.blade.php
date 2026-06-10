@extends('layouts.app')

@section('title', __('app.dashboard'))
@section('page-title', __('app.dashboard'))

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @php
        $statCards = [
            ['label' => app()->getLocale() === 'ar' ? 'إجمالي الشحنات' : 'Total Shipments', 'value' => $stats['total'], 'icon' => 'fa-box', 'color' => 'blue'],
            ['label' => app()->getLocale() === 'ar' ? 'شحنات اليوم' : "Today's", 'value' => $stats['today'], 'icon' => 'fa-calendar-day', 'color' => 'indigo'],
            ['label' => app()->getLocale() === 'ar' ? 'في الطريق' : 'In Transit', 'value' => $stats['in_transit'], 'icon' => 'fa-truck-fast', 'color' => 'yellow'],
            ['label' => app()->getLocale() === 'ar' ? 'قيد التوصيل' : 'Out for Delivery', 'value' => $stats['out_delivery'], 'icon' => 'fa-truck', 'color' => 'orange'],
            ['label' => app()->getLocale() === 'ar' ? 'تم التسليم' : 'Delivered', 'value' => $stats['delivered'], 'icon' => 'fa-circle-check', 'color' => 'green'],
            ['label' => app()->getLocale() === 'ar' ? 'فشل التسليم' : 'Failed', 'value' => $stats['failed'], 'icon' => 'fa-circle-xmark', 'color' => 'red'],
        ];
        @endphp

        @foreach($statCards as $card)
            @php
            $colorMap = [
                'blue'   => ['bg'=>'bg-blue-50', 'icon'=>'bg-blue-100 text-blue-600', 'text'=>'text-blue-700'],
                'indigo' => ['bg'=>'bg-indigo-50', 'icon'=>'bg-indigo-100 text-indigo-600', 'text'=>'text-indigo-700'],
                'yellow' => ['bg'=>'bg-yellow-50', 'icon'=>'bg-yellow-100 text-yellow-600', 'text'=>'text-yellow-700'],
                'orange' => ['bg'=>'bg-orange-50', 'icon'=>'bg-orange-100 text-orange-600', 'text'=>'text-orange-700'],
                'green'  => ['bg'=>'bg-green-50', 'icon'=>'bg-green-100 text-green-600', 'text'=>'text-green-700'],
                'red'    => ['bg'=>'bg-red-50', 'icon'=>'bg-red-100 text-red-600', 'text'=>'text-red-700'],
            ];
            $c = $colorMap[$card['color']];
            @endphp
            <div class="card p-4 {{ $c['bg'] }}">
                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 {{ $c['icon'] }} rounded-xl flex items-center justify-center">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl font-black {{ $c['text'] }}">{{ number_format($card['value']) }}</div>
                    <div class="text-xs text-gray-600 mt-0.5">{{ $card['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Shipments --}}
        <div class="lg:col-span-2 card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">
                    {{ app()->getLocale() === 'ar' ? 'آخر الشحنات' : 'Recent Shipments' }}
                </h3>
                <a href="{{ route('shipments.index') }}" class="text-blue-600 text-sm hover:underline">
                    {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }}
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table w-full">
                    <thead>
                        <tr>
                            <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.tracking_number') }}</th>
                            <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ app()->getLocale() === 'ar' ? 'المرسل إليه' : 'Receiver' }}</th>
                            <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.destination_branch') }}</th>
                            <th class="{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">{{ __('shipments.filter_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentShipments as $s)
                            <tr onclick="window.location='{{ route('shipments.show', $s) }}'" class="cursor-pointer">
                                <td class="font-mono font-semibold text-blue-700 text-xs">{{ $s->tracking_number }}</td>
                                <td>
                                    <div class="font-medium text-sm">{{ $s->receiver_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $s->receiver_phone }}</div>
                                </td>
                                <td class="text-sm">{{ $s->destinationBranch?->display_name }}</td>
                                <td>@include('shipments._status_badge', ['status' => $s->status])</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-8 text-gray-400 text-sm">{{ __('app.messages.no_results') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-6">

            {{-- Status breakdown --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 text-sm">
                        {{ app()->getLocale() === 'ar' ? 'توزيع الحالات' : 'Status Breakdown' }}
                    </h3>
                </div>
                <div class="card-body space-y-2">
                    @foreach(\App\Models\Shipment::$statuses as $status)
                        @php $count = $statusBreakdown[$status] ?? 0; $total = $stats['total'] ?: 1; @endphp
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600">{{ __('shipments.status.'.$status) }}</span>
                                <span class="font-semibold">{{ $count }}</span>
                            </div>
                            <div class="h-1.5 bg-gray-100 rounded-full">
                                <div class="h-1.5 bg-blue-500 rounded-full" style="width:{{ ($count/$total)*100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Quick stats --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 text-sm">
                        {{ app()->getLocale() === 'ar' ? 'إحصائيات النظام' : 'System Stats' }}
                    </h3>
                </div>
                <div class="card-body space-y-3">
                    @foreach([
                        ['icon'=>'fa-users', 'label'=> app()->getLocale() === 'ar' ? 'العملاء' : 'Customers', 'value'=>$totalCustomers, 'color'=>'text-blue-600'],
                        ['icon'=>'fa-building', 'label'=> app()->getLocale() === 'ar' ? 'الفروع' : 'Branches', 'value'=>$totalBranches, 'color'=>'text-indigo-600'],
                        ['icon'=>'fa-truck', 'label'=> app()->getLocale() === 'ar' ? 'السائقون' : 'Drivers', 'value'=>$totalDrivers, 'color'=>'text-purple-600'],
                    ] as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid {{ $item['icon'] }} {{ $item['color'] }} w-4 text-center"></i>
                                <span class="text-sm text-gray-600">{{ $item['label'] }}</span>
                            </div>
                            <span class="font-bold text-gray-900">{{ $item['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 text-sm">
                        {{ app()->getLocale() === 'ar' ? 'إجراءات سريعة' : 'Quick Actions' }}
                    </h3>
                </div>
                <div class="card-body space-y-2">
                    <a href="{{ route('shipments.create') }}" class="btn-primary w-full justify-center text-sm">
                        <i class="fa-solid fa-plus"></i>
                        {{ __('shipments.new') }}
                    </a>
                    <a href="{{ route('tracking') }}" class="btn-secondary w-full justify-center text-sm">
                        <i class="fa-solid fa-search"></i>
                        {{ app()->getLocale() === 'ar' ? 'تتبع شحنة' : 'Track Shipment' }}
                    </a>
                    <a href="{{ route('customers.create') }}" class="btn-secondary w-full justify-center text-sm">
                        <i class="fa-solid fa-user-plus"></i>
                        {{ app()->getLocale() === 'ar' ? 'عميل جديد' : 'New Customer' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
