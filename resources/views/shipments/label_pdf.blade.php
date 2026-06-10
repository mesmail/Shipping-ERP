<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>{{ $shipment->tracking_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
        }

        body {
            background: #ffffff;
            color: #111827;
            font-size: 11px;
            direction: rtl;
        }

        .label-wrapper {
            width: 148mm;
            min-height: 105mm;
            border: 2px solid #1e40af;
            background: #ffffff;
            overflow: hidden;
        }

        /* Header */
        .label-header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            background: #ffffff;
            padding: 6px 10px;
        }
        .label-header-logo {
            display: table-cell;
            width: 33%;
            vertical-align: middle;
        }
        .logo-box {
            display: inline-block;
            background: #1e3a8a;
            color: #ffffff;
            font-weight: 900;
            font-size: 18px;
            padding: 6px 10px;
            border-radius: 6px;
            letter-spacing: 1px;
        }
        .logo-name {
            font-size: 14px;
            font-weight: 900;
            color: #1e3a8a;
            display: block;
            margin-top: 2px;
        }
        .logo-sub {
            font-size: 9px;
            color: #4b72b0;
        }
        .label-header-tracking {
            display: table-cell;
            width: 34%;
            text-align: center;
            vertical-align: middle;
        }
        .tracking-label {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 2px;
        }
        .tracking-number {
            font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
            font-size: 16px;
            font-weight: 900;
            color: #1e3a8a;
            letter-spacing: 2px;
        }
        .label-header-qr {
            display: table-cell;
            width: 33%;
            text-align: left;
            vertical-align: middle;
        }
        .qr-img {
            width: 60px;
            height: 60px;
        }

        /* Body grid */
        .label-body {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .label-col-left {
            display: table-cell;
            width: 50%;
            border-left: 2px solid #bfdbfe;
            vertical-align: top;
        }
        .label-col-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        /* Section headers */
        .section-header {
            padding: 4px 8px;
            font-size: 10px;
            font-weight: 700;
            color: #ffffff;
        }
        .section-header-sender   { background: #1e40af; }
        .section-header-receiver { background: #065f46; }
        .section-header-details  { background: #4f46e5; }
        .section-header-pod      { background: #b45309; }

        .section-body {
            padding: 6px 8px;
            border-bottom: 1.5px solid #bfdbfe;
        }

        .person-name {
            font-size: 12px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 3px;
        }
        .info-row {
            font-size: 10px;
            color: #374151;
            margin-bottom: 2px;
            line-height: 1.4;
        }
        .info-muted {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 1px;
        }
        .instruction-box {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 3px;
            padding: 3px 5px;
            font-size: 9px;
            color: #92400e;
            margin-top: 3px;
        }

        /* Details grid */
        .details-grid {
            display: table;
            width: 100%;
            padding: 5px 6px;
        }
        .details-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 3px;
        }
        .details-big {
            font-size: 18px;
            font-weight: 900;
            color: #1e3a8a;
            display: block;
        }
        .details-label {
            font-size: 8px;
            color: #6b7280;
            display: block;
            margin-top: 1px;
        }
        .details-meta {
            padding: 0 6px 5px;
            display: table;
            width: 100%;
        }
        .details-meta-row {
            display: table-row;
        }
        .details-meta-key {
            display: table-cell;
            font-size: 9px;
            font-weight: 700;
            color: #374151;
            padding: 1px 2px;
            white-space: nowrap;
        }
        .details-meta-val {
            display: table-cell;
            font-size: 9px;
            color: #374151;
            padding: 1px 2px;
        }

        /* POD */
        .pod-body {
            padding: 5px 8px;
        }
        .pod-field {
            margin-bottom: 5px;
        }
        .pod-field-label {
            font-size: 9px;
            color: #6b7280;
            display: inline-block;
            white-space: nowrap;
        }
        .pod-field-line {
            display: inline-block;
            border-bottom: 1px solid #9ca3af;
            min-width: 100px;
            height: 14px;
            vertical-align: bottom;
            font-size: 10px;
            padding-right: 2px;
        }
        .pod-sig-box {
            border: 1px solid #d1d5db;
            border-radius: 3px;
            height: 35px;
            background: #f9fafb;
            display: block;
            margin-top: 3px;
            overflow: hidden;
        }
        .pod-sig-img {
            height: 100%;
            max-height: 35px;
        }
        .pod-date {
            font-size: 8px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* Barcode row */
        .barcode-row {
            text-align: center;
            padding: 4px;
            border-top: 1.5px solid #bfdbfe;
        }
        .barcode-img {
            height: 28px;
            max-width: 100%;
        }

        /* Footer */
        .label-footer {
            background: #1e3a8a;
            text-align: center;
            padding: 3px 8px;
        }
        .label-footer p {
            color: #bfdbfe;
            font-size: 8px;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

<div class="label-wrapper">

    {{-- Header --}}
    <div class="label-header">
        <div class="label-header-logo">
            <span class="logo-box">7X</span>
            <span class="logo-name">7expres</span>
            <span class="logo-sub">Logistics Solutions</span>
        </div>
        <div class="label-header-tracking">
            <div class="tracking-label">رقم التتبع / Tracking No.</div>
            <div class="tracking-number">{{ $shipment->tracking_number }}</div>
            @if($shipment->shipment_date)
                <div class="info-muted" style="margin-top:3px;">{{ $shipment->shipment_date->format('d/m/Y') }}</div>
            @endif
        </div>
        <div class="label-header-qr">
            <img src="{{ route('shipments.qrcode', $shipment) }}" class="qr-img" alt="QR">
        </div>
    </div>

    {{-- Body --}}
    <div class="label-body">

        {{-- Left column: Sender + Receiver --}}
        <div class="label-col-left">

            {{-- Sender --}}
            <div class="section-header section-header-sender">المرسل &mdash; Sender</div>
            <div class="section-body">
                <div class="person-name">{{ $shipment->sender_name }}</div>
                <div class="info-row">&#x1F4DE; {{ $shipment->sender_phone }}</div>
                @if($shipment->sender_phone_alt)
                    <div class="info-muted">{{ $shipment->sender_phone_alt }}</div>
                @endif
                @if($shipment->sender_address)
                    <div class="info-muted">{{ $shipment->sender_address }}</div>
                @endif
                <div class="info-muted">{{ $shipment->sender_city }}{{ $shipment->sender_city && $shipment->sender_country ? ' - ' : '' }}{{ $shipment->sender_country }}</div>
            </div>

            {{-- Receiver --}}
            <div class="section-header section-header-receiver">المستلم &mdash; Receiver</div>
            <div class="section-body">
                <div class="person-name">{{ $shipment->receiver_name }}</div>
                <div class="info-row">&#x1F4DE; {{ $shipment->receiver_phone }}</div>
                @if($shipment->receiver_phone_alt)
                    <div class="info-muted">{{ $shipment->receiver_phone_alt }}</div>
                @endif
                @if($shipment->receiver_address)
                    <div class="info-muted">{{ $shipment->receiver_address }}</div>
                @endif
                <div class="info-muted">{{ $shipment->receiver_city }}{{ $shipment->receiver_city && $shipment->receiver_country ? ' - ' : '' }}{{ $shipment->receiver_country }}</div>
                @if($shipment->delivery_instructions)
                    <div class="instruction-box">{{ $shipment->delivery_instructions }}</div>
                @endif
            </div>

            {{-- Barcode --}}
            <div class="barcode-row">
                <img src="{{ route('shipments.barcode', $shipment) }}" class="barcode-img" alt="Barcode">
            </div>
        </div>

        {{-- Right column: Details + POD --}}
        <div class="label-col-right">

            {{-- Shipment Details --}}
            <div class="section-header section-header-details">تفاصيل الشحنة &mdash; Details</div>
            <div class="details-grid">
                <div class="details-cell">
                    <span class="details-big">{{ $shipment->total_packages }}</span>
                    <span class="details-label">طرد / Pkgs</span>
                </div>
                <div class="details-cell">
                    <span class="details-big">{{ number_format($shipment->total_chargeable_weight, 2) }}</span>
                    <span class="details-label">كجم / kg</span>
                </div>
            </div>
            <div class="details-meta">
                <div class="details-meta-row">
                    <span class="details-meta-key">النوع:</span>
                    <span class="details-meta-val">{{ __('shipments.type.'.$shipment->shipment_type) }}</span>
                </div>
                <div class="details-meta-row">
                    <span class="details-meta-key">التوصيل:</span>
                    <span class="details-meta-val">{{ __('shipments.delivery_types.'.$shipment->delivery_type) }}</span>
                </div>
                <div class="details-meta-row">
                    <span class="details-meta-key">من فرع:</span>
                    <span class="details-meta-val">{{ $shipment->originBranch?->display_name ?? '—' }}</span>
                </div>
                <div class="details-meta-row">
                    <span class="details-meta-key">إلى فرع:</span>
                    <span class="details-meta-val">{{ $shipment->destinationBranch?->display_name ?? '—' }}</span>
                </div>
                <div class="details-meta-row">
                    <span class="details-meta-key">الحالة:</span>
                    <span class="details-meta-val">{{ __('shipments.status.'.$shipment->status) }}</span>
                </div>
            </div>

            {{-- POD --}}
            <div class="section-header section-header-pod">إثبات التسليم &mdash; POD</div>
            <div class="pod-body">
                <div class="pod-field">
                    <span class="pod-field-label">اسم المستلم: </span>
                    <span class="pod-field-line">{{ $shipment->pod_receiver_name }}</span>
                </div>
                <div class="pod-field">
                    <span class="pod-field-label">رقم الهوية: </span>
                    <span class="pod-field-line">{{ $shipment->pod_receiver_id }}</span>
                </div>
                <div class="pod-field">
                    <span class="pod-field-label" style="display:block; margin-bottom:2px;">التوقيع / Signature:</span>
                    <span class="pod-sig-box">
                        @if($shipment->pod_signature)
                            <img src="{{ $shipment->pod_signature }}" class="pod-sig-img" alt="Signature">
                        @endif
                    </span>
                </div>
                @if($shipment->pod_at)
                    <div class="pod-date">تاريخ التسليم: {{ $shipment->pod_at->format('d/m/Y H:i') }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="label-footer">
        <p>7expres Logistics Solutions &bull; Malaysia &#x21CB; Yemen &bull; www.7expres.com</p>
    </div>

</div>

</body>
</html>
