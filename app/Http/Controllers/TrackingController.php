<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $shipment = null;

        if ($q = $request->get('q')) {
            $shipment = Shipment::with([
                'originBranch', 'destinationBranch', 'currentBranch',
                'packages', 'events.branch',
            ])
            ->where('tracking_number', strtoupper(trim($q)))
            ->first();
        }

        return view('tracking.index', compact('shipment'));
    }
}
