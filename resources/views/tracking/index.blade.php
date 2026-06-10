<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('shipments.tracking_page.title') }} - 7expres</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        [dir="rtl"] { font-family: 'Cairo', sans-serif; }
        [dir="ltr"] { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #0ea5e9 100%); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Header --}}
    <header class="gradient-bg py-6 px-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-blue-900 font-black text-lg">7X</span>
                </div>
                <div>
                    <div class="text-white font-black text-xl">7expres</div>
                    <div class="text-blue-200 text-xs">Malaysia ⇌ Yemen</div>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
                   class="text-white text-sm bg-white bg-opacity-20 px-3 py-1.5 rounded-lg hover:bg-opacity-30 transition">
                    {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-white text-sm bg-white bg-opacity-20 px-3 py-1.5 rounded-lg hover:bg-opacity-30 transition">
                        {{ __('app.dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white text-sm bg-white bg-opacity-20 px-3 py-1.5 rounded-lg hover:bg-opacity-30 transition">
                        {{ __('app.login') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Search Section --}}
    <div class="gradient-bg pb-12 px-4">
        <div class="max-w-2xl mx-auto text-center py-8">
            <h1 class="text-3xl font-black text-white mb-2">{{ __('shipments.tracking_page.title') }}</h1>
            <p class="text-blue-200 mb-8">{{ __('shipments.tracking_page.subtitle') }}</p>

            <form method="GET" action="{{ route('tracking') }}" class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="{{ __('shipments.tracking_page.placeholder') }}"
                       class="flex-1 px-4 py-3 rounded-xl text-gray-900 text-sm outline-none shadow-lg
                              {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                       autofocus>
                <button type="submit"
                        class="bg-amber-400 hover:bg-amber-300 text-gray-900 font-bold px-6 py-3 rounded-xl shadow-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-search"></i>
                    {{ __('shipments.tracking_page.track') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Results --}}
    <div class="max-w-4xl mx-auto px-4 -mt-4 pb-16">

        @if(isset($shipment))
            {{-- Shipment found --}}
            <div class="space-y-4">

                {{-- Current Status Card --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <div class="text-gray-500 text-xs mb-1">{{ __('shipments.tracking_number') }}</div>
                            <div class="font-mono font-black text-2xl text-blue-900">{{ $shipment->tracking_number }}</div>
                        </div>
                        @include('shipments._status_badge', ['status' => $shipment->status])
                    </div>

                    {{-- Progress --}}
                    @php
                        $statOrder = ['created','collected','in_transit','transferred','arrived_at_branch','out_for_delivery','delivered'];
                        $curIdx = array_search($shipment->status, $statOrder);
                    @endphp
                    @if(!in_array($shipment->status, ['failed_delivery','returned']))
                    <div class="px-6 pb-6">
                        <div class="flex items-center">
                            @foreach($statOrder as $i => $st)
                                <div class="flex flex-col items-center flex-1 relative">
                                    @if($i < count($statOrder)-1)
                                        <div class="absolute h-0.5 top-4 {{ app()->getLocale() === 'ar' ? 'right-1/2' : 'left-1/2' }} w-full z-0
                                            {{ $i < $curIdx ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                    @endif
                                    <div class="w-8 h-8 rounded-full z-10 relative flex items-center justify-center
                                        {{ $i < $curIdx ? 'bg-blue-600 text-white' : ($i == $curIdx ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 'bg-gray-100 text-gray-400') }}">
                                        @if($i < $curIdx)
                                            <i class="fa-solid fa-check text-xs"></i>
                                        @else
                                            <span class="text-xs">{{ $i+1 }}</span>
                                        @endif
                                    </div>
                                    <div class="text-xs mt-1 text-center hidden md:block {{ $i <= $curIdx ? 'text-blue-700 font-semibold' : 'text-gray-400' }}">
                                        {{ __('shipments.status.'.$st) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Shipment Details --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h3 class="font-bold text-gray-900 mb-3">{{ __('shipments.tracking_page.shipment_details') }}</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('shipments.shipment_date') }}</span>
                                <span class="font-medium">{{ $shipment->shipment_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('shipments.shipment_type') }}</span>
                                <span class="font-medium">{{ __('shipments.type.'.$shipment->shipment_type) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('shipments.origin_branch') }}</span>
                                <span class="font-medium">{{ $shipment->originBranch?->display_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('shipments.destination_branch') }}</span>
                                <span class="font-medium">{{ $shipment->destinationBranch?->display_name }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Package Summary --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h3 class="font-bold text-gray-900 mb-3">{{ __('shipments.tracking_page.package_summary') }}</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-blue-50 rounded-xl p-3 text-center">
                                <div class="text-2xl font-black text-blue-700">{{ $shipment->total_packages }}</div>
                                <div class="text-xs text-gray-500">{{ __('shipments.packages.total_packages') }}</div>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-3 text-center">
                                <div class="text-2xl font-black text-blue-700">{{ number_format($shipment->total_chargeable_weight, 2) }}</div>
                                <div class="text-xs text-gray-500">kg</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="bg-white rounded-2xl shadow-sm p-5">
                    <h3 class="font-bold text-gray-900 mb-4">{{ __('shipments.timeline.title') }}</h3>
                    <div class="space-y-0">
                        @foreach($shipment->events as $event)
                            @php
                                $ec = ['created'=>'border-blue-500','collected'=>'border-indigo-500',
                                       'in_transit'=>'border-yellow-500','transferred'=>'border-purple-500',
                                       'arrived_at_branch'=>'border-cyan-500','out_for_delivery'=>'border-orange-500',
                                       'delivered'=>'border-green-500','received_closed'=>'border-emerald-500',
                                       'failed_delivery'=>'border-red-500','returned'=>'border-gray-400'][$event->status] ?? 'border-gray-400';
                            @endphp
                            <div class="flex gap-4 {{ !$loop->last ? 'pb-6' : '' }} relative">
                                @if(!$loop->last)
                                    <div class="absolute {{ app()->getLocale() === 'ar' ? 'right-3.5' : 'left-3.5' }} top-8 bottom-0 w-0.5 bg-gray-200"></div>
                                @endif
                                <div class="w-7 h-7 rounded-full border-2 {{ $ec }} bg-white flex-shrink-0 z-10 flex items-center justify-center">
                                    <div class="w-2 h-2 rounded-full bg-current" style="background:inherit"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="font-semibold text-gray-900 text-sm">{{ __('shipments.status.'.$event->status) }}</div>
                                            @if($event->branch)
                                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                                    <i class="fa-solid fa-building text-xs"></i>
                                                    {{ $event->branch->display_name }}
                                                </div>
                                            @endif
                                            @if($event->notes)
                                                <div class="text-xs text-gray-600 mt-1 bg-gray-50 rounded px-2 py-1">{{ $event->notes }}</div>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-400 whitespace-nowrap {{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                            {{ $event->event_at->format('d/m/Y') }}<br>
                                            {{ $event->event_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @elseif(request('q') && !isset($shipment))
            {{-- Not found --}}
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <i class="fa-solid fa-magnifying-glass text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">{{ __('shipments.tracking_page.not_found') }}</h3>
                <p class="text-gray-500 text-sm">{{ app()->getLocale() === 'ar' ? 'رقم التتبع غير صحيح أو لم يتم الإنشاء بعد' : 'The tracking number is invalid or not yet created' }}</p>
                <p class="mt-2 font-mono text-blue-600 font-semibold">{{ request('q') }}</p>
            </div>

        @else
            {{-- Empty state --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                @foreach([
                    ['icon'=>'fa-box', 'title'=> app()->getLocale() === 'ar' ? 'شحن موثوق' : 'Reliable Shipping', 'desc'=> app()->getLocale() === 'ar' ? 'نقل آمن بين ماليزيا واليمن' : 'Safe transport Malaysia & Yemen'],
                    ['icon'=>'fa-clock', 'title'=> app()->getLocale() === 'ar' ? 'تتبع مباشر' : 'Live Tracking', 'desc'=> app()->getLocale() === 'ar' ? 'تتبع شحنتك في الوقت الحقيقي' : 'Track your shipment in real time'],
                    ['icon'=>'fa-headset', 'title'=> app()->getLocale() === 'ar' ? 'دعم 24/7' : '24/7 Support', 'desc'=> app()->getLocale() === 'ar' ? 'فريق دعم متاح على مدار الساعة' : 'Our team is available round the clock'],
                ] as $feature)
                    <div class="bg-white rounded-2xl shadow-sm p-5 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid {{ $feature['icon'] }} text-blue-600 text-lg"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">{{ $feature['title'] }}</h3>
                        <p class="text-gray-500 text-sm">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <footer class="bg-blue-900 text-blue-200 text-center py-6 text-sm">
        <p>© 2024 7expres Logistics Solutions | Malaysia ⇌ Yemen</p>
    </footer>
</body>
</html>
