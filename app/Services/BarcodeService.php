<?php

namespace App\Services;

use Illuminate\Http\Response;
use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarcodeService
{
    public function generateBarcodeResponse(string $value): Response
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($value, $generator::TYPE_CODE_128, 3, 80);

        return response($barcode, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function generateQrcodeResponse(string $value): Response
    {
        $qr = QrCode::format('png')
            ->size(200)
            ->errorCorrection('H')
            ->generate($value);

        return response($qr, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function getBarcodeBase64(string $value): string
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($value, $generator::TYPE_CODE_128, 3, 80);
        return 'data:image/png;base64,' . base64_encode($barcode);
    }

    public function getQrcodeBase64(string $value): string
    {
        $qr = QrCode::format('png')->size(200)->generate($value);
        return 'data:image/png;base64,' . base64_encode($qr);
    }
}
