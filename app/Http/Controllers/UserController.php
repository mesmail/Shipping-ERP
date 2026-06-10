<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with(['roles','branch'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->paginate(20);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $branches = Branch::active()->get();
        $roles = Role::all();
        return view('users.create', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
            'role'      => 'required|exists:roles,name',
            'locale'    => 'in:ar,en',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'branch_id' => $request->branch_id,
            'locale'    => $request->locale ?? 'ar',
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم إضافة المستخدم' : 'User created');
    }

    public function edit(User $user)
    {
        $branches = Branch::active()->get();
        $roles = Role::all();
        return view('users.edit', compact('user', 'branches', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'branch_id' => 'nullable|exists:branches,id',
            'role'      => 'required|exists:roles,name',
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'branch_id' => $request->branch_id,
            'locale'    => $request->locale ?? 'ar',
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم التحديث' : 'Updated');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', app()->getLocale() === 'ar' ? 'لا يمكن حذف حسابك الخاص' : 'Cannot delete your own account');
        }
        $user->delete();
        return back()->with('success', app()->getLocale() === 'ar' ? 'تم الحذف' : 'Deleted');
    }
}
