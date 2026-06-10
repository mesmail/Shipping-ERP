@php
$colors = [
    'created'           => 'bg-blue-100 text-blue-800',
    'collected'         => 'bg-indigo-100 text-indigo-800',
    'in_transit'        => 'bg-yellow-100 text-yellow-800',
    'transferred'       => 'bg-purple-100 text-purple-800',
    'arrived_at_branch' => 'bg-cyan-100 text-cyan-800',
    'out_for_delivery'  => 'bg-orange-100 text-orange-800',
    'delivered'         => 'bg-green-100 text-green-800',
    'received_closed'   => 'bg-emerald-100 text-emerald-800',
    'failed_delivery'   => 'bg-red-100 text-red-800',
    'returned'          => 'bg-gray-100 text-gray-800',
];
$dots = [
    'created'           => 'bg-blue-500',
    'collected'         => 'bg-indigo-500',
    'in_transit'        => 'bg-yellow-500',
    'transferred'       => 'bg-purple-500',
    'arrived_at_branch' => 'bg-cyan-500',
    'out_for_delivery'  => 'bg-orange-500',
    'delivered'         => 'bg-green-500',
    'received_closed'   => 'bg-emerald-500',
    'failed_delivery'   => 'bg-red-500',
    'returned'          => 'bg-gray-500',
];
$color = $colors[$status] ?? 'bg-gray-100 text-gray-800';
$dot = $dots[$status] ?? 'bg-gray-500';
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $color }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    {{ __('shipments.status.'.$status) }}
</span>
