<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Driver;
use App\Models\Package;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Services\BarcodeService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ShipmentController extends Controller
{
    public function __construct(
        private BarcodeService $barcodeService,
        private NotificationService $notificationService,
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Shipment::with(['originBranch','destinationBranch','currentBranch','driver'])
            ->search($request->search);

        // Branch-restricted users only see their branch shipments
        if ($user->hasRole('branch_user') && $user->branch_id) {
            $query->forBranch($user->branch_id);
        }

        if ($request->status) $query->where('status', $request->status);
        if ($request->branch_id) {
            $query->where(function ($q) use ($request) {
                $q->where('origin_branch_id', $request->branch_id)
                  ->orWhere('destination_branch_id', $request->branch_id)
                  ->orWhere('current_branch_id', $request->branch_id);
            });
        }
        if ($request->driver_id) $query->where('driver_id', $request->driver_id);
        if ($request->date_from) $query->whereDate('shipment_date', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('shipment_date', '<=', $request->date_to);

        $shipments = $query->orderByDesc('created_at')->paginate(20);

        return view('shipments.index', [
            'shipments' => $shipments,
            'branches'  => Branch::active()->get(),
            'drivers'   => Driver::active()->get(),
        ]);
    }

    public function create()
    {
        return view('shipments.create', [
            'branches' => Branch::active()->get(),
            'drivers'  => Driver::active()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_date'        => 'required|date',
            'shipment_type'        => 'required|in:standard,express,economy,fragile,documents,oversized',
            'delivery_type'        => 'required|in:door_to_door,branch_pickup,locker',
            'origin_branch_id'     => 'required|exists:branches,id',
            'destination_branch_id'=> 'required|exists:branches,id',
            'driver_id'            => 'nullable|exists:drivers,id',
            'sender_name'          => 'required|string|max:255',
            'sender_phone'         => 'required|string|max:30',
            'sender_phone_alt'     => 'nullable|string|max:30',
            'sender_email'         => 'nullable|email',
            'sender_address'       => 'nullable|string',
            'sender_city'          => 'nullable|string',
            'sender_country'       => 'nullable|string',
            'sender_id_number'     => 'nullable|string',
            'receiver_name'        => 'required|string|max:255',
            'receiver_phone'       => 'required|string|max:30',
            'receiver_phone_alt'   => 'nullable|string|max:30',
            'receiver_address'     => 'nullable|string',
            'receiver_city'        => 'nullable|string',
            'receiver_country'     => 'nullable|string',
            'delivery_instructions'=> 'nullable|string',
            'notes'                => 'nullable|string',
            'packages'             => 'required|array|min:1',
            'packages.*.description'   => 'nullable|string',
            'packages.*.length'        => 'required|numeric|min:0',
            'packages.*.width'         => 'required|numeric|min:0',
            'packages.*.height'        => 'required|numeric|min:0',
            'packages.*.actual_weight' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $shipment = Shipment::create([
                ...$validated,
                'tracking_number'   => Shipment::generateTrackingNumber(),
                'current_branch_id' => $validated['origin_branch_id'],
                'sender_id'         => $request->sender_id,
                'receiver_id'       => $request->receiver_id,
                'created_by'        => Auth::id(),
                'updated_by'        => Auth::id(),
            ]);

            foreach ($validated['packages'] as $i => $pkgData) {
                $pkg = new Package([
                    'package_number' => $i + 1,
                    'description'    => $pkgData['description'] ?? null,
                    'length'         => $pkgData['length'],
                    'width'          => $pkgData['width'],
                    'height'         => $pkgData['height'],
                    'actual_weight'  => $pkgData['actual_weight'],
                ]);
                $pkg->calculateWeights();
                $shipment->packages()->save($pkg);
            }

            $shipment->updateTotals();

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status'      => 'created',
                'branch_id'   => $shipment->origin_branch_id,
                'user_id'     => Auth::id(),
                'event_at'    => now(),
                'notes'       => $validated['notes'] ?? null,
            ]);

            $this->notificationService->notifyStatusChange($shipment);

            session()->flash('success', __('shipments.created_success'));
            session()->flash('new_tracking', $shipment->tracking_number);
        });

        return redirect()->route('shipments.index');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['originBranch','destinationBranch','currentBranch','driver','packages','events.branch','events.user','createdBy']);
        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $shipment->load('packages');
        return view('shipments.edit', [
            'shipment' => $shipment,
            'branches' => Branch::active()->get(),
            'drivers'  => Driver::active()->get(),
        ]);
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'shipment_date'        => 'required|date',
            'shipment_type'        => 'required|in:standard,express,economy,fragile,documents,oversized',
            'delivery_type'        => 'required|in:door_to_door,branch_pickup,locker',
            'origin_branch_id'     => 'required|exists:branches,id',
            'destination_branch_id'=> 'required|exists:branches,id',
            'driver_id'            => 'nullable|exists:drivers,id',
            'sender_name'          => 'required|string',
            'sender_phone'         => 'required|string',
            'sender_phone_alt'     => 'nullable|string',
            'sender_email'         => 'nullable|email',
            'sender_address'       => 'nullable|string',
            'sender_city'          => 'nullable|string',
            'sender_country'       => 'nullable|string',
            'sender_id_number'     => 'nullable|string',
            'receiver_name'        => 'required|string',
            'receiver_phone'       => 'required|string',
            'receiver_phone_alt'   => 'nullable|string',
            'receiver_address'     => 'nullable|string',
            'receiver_city'        => 'nullable|string',
            'receiver_country'     => 'nullable|string',
            'delivery_instructions'=> 'nullable|string',
            'notes'                => 'nullable|string',
        ]);

        $shipment->update(array_merge($validated, ['updated_by' => Auth::id()]));

        return redirect()->route('shipments.show', $shipment)
            ->with('success', __('shipments.updated_success'));
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', Shipment::$statuses),
            'notes'  => 'nullable|string',
        ]);

        $oldStatus = $shipment->status;

        DB::transaction(function () use ($request, $shipment, $oldStatus) {
            $shipment->update([
                'status'     => $request->status,
                'updated_by' => Auth::id(),
                'current_branch_id' => Auth::user()->branch_id ?? $shipment->current_branch_id,
            ]);

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status'      => $request->status,
                'branch_id'   => Auth::user()->branch_id ?? $shipment->current_branch_id,
                'user_id'     => Auth::id(),
                'event_at'    => now(),
                'notes'       => $request->notes,
            ]);

            if ($oldStatus !== $request->status) {
                $this->notificationService->notifyStatusChange($shipment);
            }
        });

        return back()->with('success', __('shipments.status_updated'));
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return redirect()->route('shipments.index')
            ->with('success', __('shipments.deleted_success'));
    }

    public function label(Shipment $shipment)
    {
        $shipment->load(['originBranch','destinationBranch','currentBranch','packages']);
        return view('shipments.label', compact('shipment'));
    }

    public function labelPdf(Shipment $shipment)
    {
        $shipment->load(['originBranch','destinationBranch','currentBranch','packages']);
        $barcodeBase64 = $this->barcodeService->getBarcodeBase64($shipment->tracking_number);
        $qrBase64 = $this->barcodeService->getQrcodeBase64(
            url('/track?q=' . $shipment->tracking_number)
        );
        $pdf = Pdf::loadView('shipments.label_pdf', compact('shipment', 'barcodeBase64', 'qrBase64'))
            ->setPaper('a5', 'landscape');
        return $pdf->download($shipment->tracking_number . '-label.pdf');
    }

    public function barcode(Shipment $shipment)
    {
        return $this->barcodeService->generateBarcodeResponse($shipment->tracking_number);
    }

    public function qrcode(Shipment $shipment)
    {
        return $this->barcodeService->generateQrcodeResponse(
            route('tracking', ['q' => $shipment->tracking_number])
        );
    }
}
