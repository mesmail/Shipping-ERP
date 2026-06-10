<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Shipment::query();
        if ($user->hasRole('branch_user') && $user->branch_id) {
            $query->forBranch($user->branch_id);
        }

        $stats = [
            'total'         => (clone $query)->count(),
            'today'         => (clone $query)->whereDate('shipment_date', today())->count(),
            'in_transit'    => (clone $query)->where('status', 'in_transit')->count(),
            'delivered'     => (clone $query)->where('status', 'delivered')->count(),
            'failed'        => (clone $query)->where('status', 'failed_delivery')->count(),
            'out_delivery'  => (clone $query)->where('status', 'out_for_delivery')->count(),
        ];

        $statusBreakdown = (clone $query)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentShipments = (clone $query)
            ->with(['originBranch','destinationBranch'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $totalCustomers = Customer::count();
        $totalBranches  = Branch::active()->count();
        $totalDrivers   = Driver::active()->count();

        return view('dashboard.index', compact(
            'stats', 'statusBreakdown', 'recentShipments',
            'totalCustomers', 'totalBranches', 'totalDrivers'
        ));
    }
}
