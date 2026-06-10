<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount(['originShipments','destinationShipments','users','drivers'])
            ->orderBy('name')->paginate(20);
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code'    => 'required|string|max:10|unique:branches',
            'city'    => 'required|string',
            'city_ar' => 'nullable|string',
            'country' => 'required|string',
            'phone'   => 'nullable|string',
            'email'   => 'nullable|email',
        ]);

        Branch::create($request->all());

        return redirect()->route('branches.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم إضافة الفرع بنجاح' : 'Branch created successfully');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|max:10|unique:branches,code,' . $branch->id,
            'city'    => 'required|string',
            'country' => 'required|string',
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم التحديث' : 'Updated');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return back()->with('success', app()->getLocale() === 'ar' ? 'تم الحذف' : 'Deleted');
    }
}
