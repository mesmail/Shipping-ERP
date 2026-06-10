<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'DejaVu Sans', Arial, sans-serif; }
body { background: #fff; font-size: 10px; color: #111; }
.label { width: 100%; border: 2px solid #1e40af; }
.header { display: table; width: 100%; background: #fff; border-bottom: 2px solid #1e40af; padding: 8px; }
.header-logo { display: table-cell; width: 120px; vertical-align: middle; }
.header-tracking { display: table-cell; text-align: center; vertical-align: middle; }
.header-qr { display: table-cell; width: 90px; text-align: center; vertical-align: middle; }
.logo-box { background: #1e3a8a; color: #fff; font-weight: 900; font-size: 20px; width: 50px; height: 50px; display: inline-block; text-align: center; line-height: 50px; border-radius: 8px; }
.company-name { font-size: 16px; font-weight: 900; color: #1e3a8a; }
.tracking-num { font-size: 20px; font-weight: 900; color: #1e3a8a; font-family: monospace; letter-spacing: 2px; }
.body { display: table; width: 100%; }
.col-left { display: table-cell; width: 50%; border-left: 2px solid #bfdbfe; padding: 0; vertical-align: top; }
.col-right { display: table-cell; width: 50%; padding: 0; vertical-align: top; }
.section-title { background: #1e40af; color: #fff; padding: 3px 8px; font-size: 9px; font-weight: 700; }
.section-title.green { background: #065f46; }
.section-title.purple { background: #4f46e5; }
.section-title.amber { background: #b45309; }
.section-body { padding: 6px 8px; }
.bold { font-weight: 700; font-size: 11px; }
.label-row { margin-bottom: 3px; }
.label-small { color: #666; font-size: 8px; }
.grid2 { display: table; width: 100%; }
.grid2-cell { display: table-cell; width: 50%; text-align: center; padding: 4px; background: #f8fafc; border: 1px solid #e2e8f0; }
.grid2-val { font-size: 16px; font-weight: 900; color: #1e3a8a; }
.grid2-lbl { font-size: 8px; color: #666; }
.pod-line { border-bottom: 1px solid #999; height: 22px; margin-bottom: 5px; display: block; }
.barcode-img { max-width: 100%; height: 35px; }
.qr-img { width: 70px; height: 70px; }
.footer { background: #1e3a8a; color: #bfdbfe; text-align: center; padding: 4px; font-size: 8px; }
.divider { border-top: 2px solid #bfdbfe; }
</style>
</head>
<body>
<div class="label">
    <!-- Header -->
    <div class="header">
        <div class="header-logo">
            <div class="logo-box">7X</div>
            <div class="company-name">7expres</div>
            <div style="font-size:8px;color:#4b5563">Logistics Solutions</div>
            <div style="font-size:8px;color:#4b5563">Malaysia ⇌ Yemen</div>
        </div>
        <div class="header-tracking">
            <div style="font-size:8px;color:#6b7280;margin-bottom:3px">رقم التتبع / Tracking Number</div>
            <div class="tracking-num">{{ $shipment->tracking_number }}</div>
            <div style="margin-top:4px">
                @if($barcodeBase64)
                    <img src="{{ $barcodeBase64 }}" class="barcode-img" alt="Barcode">
                @endif
            </div>
        </div>
        <div class="header-qr">
            @if($qrBase64)
                <img src="{{ $qrBase64 }}" class="qr-img" alt="QR">
            @endif
            <div style="font-size:7px;color:#6b7280">QR Code</div>
        </div>
    </div>

    <!-- Body -->
    <div class="body">
        <div class="col-left">
            <!-- Sender -->
            <div class="section-title">المرسل - Sender</div>
            <div class="section-body">
                <div class="bold label-row">{{ $shipment->sender_name }}</div>
                <div class="label-row">📞 {{ $shipment->sender_phone }}</div>
                @if($shipment->sender_phone_alt)
                    <div class="label-row label-small">{{ $shipment->sender_phone_alt }}</div>
                @endif
                <div class="label-row">{{ $shipment->sender_address }}</div>
                <div class="label-row">{{ $shipment->sender_city }} - {{ $shipment->sender_country }}</div>
            </div>

            <div class="divider"></div>

            <!-- Receiver -->
            <div class="section-title green">المستلم - Receiver</div>
            <div class="section-body">
                <div class="bold label-row">{{ $shipment->receiver_name }}</div>
                <div class="label-row">📞 {{ $shipment->receiver_phone }}</div>
                @if($shipment->receiver_phone_alt)
                    <div class="label-row label-small">{{ $shipment->receiver_phone_alt }}</div>
                @endif
                <div class="label-row">{{ $shipment->receiver_address }}</div>
                <div class="label-row">{{ $shipment->receiver_city }} - {{ $shipment->receiver_country }}</div>
                @if($shipment->delivery_instructions)
                    <div class="label-row" style="color:#92400e;font-size:8px;background:#fffbeb;padding:2px 4px;border-radius:3px">⚠️ {{ $shipment->delivery_instructions }}</div>
                @endif
            </div>
        </div>

        <div class="col-right">
            <!-- Details -->
            <div class="section-title purple">تفاصيل الشحنة - Details</div>
            <div class="section-body">
                <div class="grid2">
                    <div class="grid2-cell">
                        <div class="grid2-val">{{ $shipment->total_packages }}</div>
                        <div class="grid2-lbl">طرد / Pkgs</div>
                    </div>
                    <div class="grid2-cell">
                        <div class="grid2-val">{{ number_format($shipment->total_chargeable_weight, 2) }}</div>
                        <div class="grid2-lbl">كجم / kg</div>
                    </div>
                </div>
                <div style="margin-top:5px">
                    <div class="label-row"><span style="font-weight:700">النوع:</span> {{ $shipment->shipment_type }}</div>
                    <div class="label-row"><span style="font-weight:700">من:</span> {{ $shipment->originBranch?->name }}</div>
                    <div class="label-row"><span style="font-weight:700">إلى:</span> {{ $shipment->destinationBranch?->name }}</div>
                    <div class="label-row"><span style="font-weight:700">التاريخ:</span> {{ $shipment->shipment_date->format('d/m/Y') }}</div>
                    <div class="label-row"><span style="font-weight:700">الحالة:</span> {{ $shipment->status }}</div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- POD -->
            <div class="section-title amber">إثبات التسليم - POD</div>
            <div class="section-body">
                <div class="label-row"><span class="label-small">اسم المستلم:</span><span class="pod-line">{{ $shipment->pod_receiver_name }}</span></div>
                <div class="label-row"><span class="label-small">رقم الهوية:</span><span class="pod-line">{{ $shipment->pod_receiver_id }}</span></div>
                <div class="label-small">التوقيع / Signature:</div>
                <div style="border: 1px solid #d1d5db; height: 35px; border-radius: 3px; margin-top: 3px;">
                    @if($shipment->pod_signature)
                        <img src="{{ $shipment->pod_signature }}" style="height:100%">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        7expres Logistics Solutions | Malaysia ⇌ Yemen | www.7expres.com
    </div>
</div>
</body>
</html>
