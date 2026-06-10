<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوليصة الشحن - {{ $shipment->tracking_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .label-border { border: 2px solid #1e40af; }
        .section-header { background: #1e40af; color: white; padding: 4px 10px; font-size: 11px; font-weight: 700; }
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            @page { size: A5 landscape; margin: 5mm; }
        }
    </style>
</head>
<body class="bg-gray-200 min-h-screen p-4">

    {{-- Print controls --}}
    <div class="no-print flex items-center gap-3 mb-4 max-w-4xl mx-auto">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            طباعة
        </button>
        <a href="{{ route('shipments.label.pdf', $shipment) }}"
           class="bg-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium hover:bg-red-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            تحميل PDF
        </a>
        <a href="{{ route('shipments.show', $shipment) }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium hover:bg-gray-700">
            ← رجوع
        </a>
    </div>

    {{-- Label --}}
    <div class="max-w-4xl mx-auto bg-white label-border rounded" style="min-height:400px">

        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b-2 border-blue-900">
            <div class="flex items-center gap-3">
                <div class="w-16 h-16 bg-blue-900 rounded-xl flex items-center justify-center">
                    <span class="text-white font-black text-2xl">7X</span>
                </div>
                <div>
                    <div class="font-black text-blue-900 text-xl leading-tight">7expres</div>
                    <div class="text-blue-700 text-sm font-semibold">Logistics Solutions</div>
                    <div class="text-gray-500 text-xs">ماليزيا ⇌ اليمن</div>
                </div>
            </div>
            <div class="text-center">
                <div class="text-xs text-gray-500 mb-1">رقم التتبع</div>
                <div class="font-mono font-black text-2xl text-blue-900 tracking-wider">{{ $shipment->tracking_number }}</div>
                <div class="mt-2">
                    <img src="{{ route('shipments.barcode', $shipment) }}" alt="Barcode" class="h-12 mx-auto">
                </div>
            </div>
            <div class="text-center">
                <img src="{{ route('shipments.qrcode', $shipment) }}" alt="QR" class="w-24 h-24 mx-auto">
                <div class="text-xs text-gray-400 mt-1">QR Code</div>
            </div>
        </div>

        <div class="grid grid-cols-2 divide-x-2 divide-blue-200" style="direction:rtl">

            {{-- Left side - Sender / Receiver --}}
            <div class="divide-y-2 divide-blue-200">

                {{-- Sender --}}
                <div>
                    <div class="section-header">المرسل - Sender</div>
                    <div class="p-3 text-sm space-y-1">
                        <div class="font-bold text-gray-900 text-base">{{ $shipment->sender_name }}</div>
                        <div class="text-gray-700">📞 {{ $shipment->sender_phone }}</div>
                        @if($shipment->sender_phone_alt)
                            <div class="text-gray-600 text-xs">{{ $shipment->sender_phone_alt }}</div>
                        @endif
                        <div class="text-gray-600">{{ $shipment->sender_address }}</div>
                        <div class="text-gray-600">{{ $shipment->sender_city }} - {{ $shipment->sender_country }}</div>
                    </div>
                </div>

                {{-- Receiver --}}
                <div>
                    <div class="section-header" style="background:#065f46">المستلم - Receiver</div>
                    <div class="p-3 text-sm space-y-1">
                        <div class="font-bold text-gray-900 text-base">{{ $shipment->receiver_name }}</div>
                        <div class="text-gray-700">📞 {{ $shipment->receiver_phone }}</div>
                        @if($shipment->receiver_phone_alt)
                            <div class="text-gray-600 text-xs">{{ $shipment->receiver_phone_alt }}</div>
                        @endif
                        <div class="text-gray-600">{{ $shipment->receiver_address }}</div>
                        <div class="text-gray-600">{{ $shipment->receiver_city }} - {{ $shipment->receiver_country }}</div>
                        @if($shipment->delivery_instructions)
                            <div class="text-amber-700 text-xs bg-amber-50 p-1 rounded mt-1">⚠️ {{ $shipment->delivery_instructions }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right side - Shipment details + POD --}}
            <div class="divide-y-2 divide-blue-200">

                {{-- Shipment details --}}
                <div>
                    <div class="section-header" style="background:#4f46e5">تفاصيل الشحنة - Details</div>
                    <div class="p-3">
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="bg-gray-50 rounded p-2 text-center">
                                <div class="text-xl font-black text-blue-900">{{ $shipment->total_packages }}</div>
                                <div class="text-xs text-gray-500">طرد / Pkgs</div>
                            </div>
                            <div class="bg-gray-50 rounded p-2 text-center">
                                <div class="text-xl font-black text-blue-900">{{ number_format($shipment->total_chargeable_weight, 2) }}</div>
                                <div class="text-xs text-gray-500">كجم / kg</div>
                            </div>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-1 text-xs text-gray-600">
                            <div><span class="font-semibold">النوع:</span> {{ __('shipments.type.'.$shipment->shipment_type) }}</div>
                            <div><span class="font-semibold">التوصيل:</span> {{ __('shipments.delivery_types.'.$shipment->delivery_type) }}</div>
                            <div><span class="font-semibold">من فرع:</span> {{ $shipment->originBranch?->display_name }}</div>
                            <div><span class="font-semibold">إلى فرع:</span> {{ $shipment->destinationBranch?->display_name }}</div>
                            <div><span class="font-semibold">التاريخ:</span> {{ $shipment->shipment_date->format('d/m/Y') }}</div>
                            <div><span class="font-semibold">الحالة:</span> {{ __('shipments.status.'.$shipment->status) }}</div>
                        </div>
                    </div>
                </div>

                {{-- POD --}}
                <div>
                    <div class="section-header" style="background:#b45309">إثبات التسليم - POD</div>
                    <div class="p-3 space-y-2">
                        <div class="flex items-end gap-2">
                            <span class="text-xs text-gray-500 whitespace-nowrap">اسم المستلم:</span>
                            <div class="flex-1 border-b border-gray-400" style="height:22px">
                                <span class="text-sm">{{ $shipment->pod_receiver_name }}</span>
                            </div>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-xs text-gray-500 whitespace-nowrap">رقم الهوية:</span>
                            <div class="flex-1 border-b border-gray-400" style="height:22px">
                                <span class="text-sm">{{ $shipment->pod_receiver_id }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">التوقيع / Signature:</div>
                            <div class="border border-gray-300 rounded" style="height:50px">
                                @if($shipment->pod_signature)
                                    <img src="{{ $shipment->pod_signature }}" class="h-full mx-auto">
                                @endif
                            </div>
                        </div>
                        @if($shipment->pod_at)
                            <div class="text-xs text-gray-500">تاريخ التسليم: {{ $shipment->pod_at->format('d/m/Y H:i') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-2 bg-blue-900 text-center">
            <p class="text-blue-200 text-xs">7expres Logistics Solutions | Malaysia ⇌ Yemen | www.7expres.com</p>
        </div>
    </div>
</body>
</html>
