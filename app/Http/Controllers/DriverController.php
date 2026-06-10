<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $drivers = Driver::with('branch')
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
            )
            ->withCount('shipments')
            ->orderBy('name')->paginate(20);

        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        $branches = Branch::active()->get();
        return view('drivers.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:30',
            'branch_id'      => 'nullable|exists:branches,id',
            'license_number' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
        ]);

        Driver::create($request->all());

        return redirect()->route('drivers.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم إضافة السائق' : 'Driver created');
    }

    public function edit(Driver $driver)
    {
        $branches = Branch::active()->get();
        return view('drivers.edit', compact('driver', 'branches'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:30',
        ]);

        $driver->update($request->all());

        return redirect()->route('drivers.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم التحديث' : 'Updated');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return back()->with('success', app()->getLocale() === 'ar' ? 'تم الحذف' : 'Deleted');
    }
}
