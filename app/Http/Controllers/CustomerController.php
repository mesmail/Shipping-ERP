<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::when($request->search, fn($q) =>
            $q->where('full_name', 'like', "%{$request->search}%")
              ->orWhere('phone', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%")
        )->orderByDesc('created_at')->paginate(20);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'required|string|max:30',
            'phone_alt' => 'nullable|string|max:30',
            'email'     => 'nullable|email',
            'address'   => 'nullable|string',
            'city'      => 'nullable|string',
            'country'   => 'nullable|string',
            'id_number' => 'nullable|string',
            'type'      => 'required|in:sender,receiver,both',
            'notes'     => 'nullable|string',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم إضافة العميل بنجاح' : 'Customer created successfully');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'required|string|max:30',
            'type'      => 'required|in:sender,receiver,both',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم التحديث بنجاح' : 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', app()->getLocale() === 'ar' ? 'تم الحذف' : 'Deleted');
    }

    public function search(Request $request)
    {
        $customers = Customer::where('full_name', 'like', "%{$request->q}%")
            ->orWhere('phone', 'like', "%{$request->q}%")
            ->limit(10)
            ->get(['id','full_name','phone','phone_alt','email','address','city','country','id_number']);

        return response()->json($customers);
    }
}
