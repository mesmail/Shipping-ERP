<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $users = User::with(['branch', 'roles'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->role, fn($q) =>
                $q->whereHas('roles', fn($r) => $r->where('name', $request->role))
            )
            ->when($request->branch_id, fn($q) =>
                $q->where('branch_id', $request->branch_id)
            )
            ->orderBy('name')
            ->paginate(20);

        $branches = Branch::active()->orderBy('name')->get();

        return view('users.index', compact('users', 'branches'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $branches = Branch::active()->orderBy('name')->get();
        $roles = ['admin', 'branch_user', 'customer'];

        return view('users.create', compact('branches', 'roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'branch_id'             => 'nullable|exists:branches,id',
            'role'                  => 'required|in:admin,branch_user,customer',
            'locale'                => 'nullable|in:ar,en',
            'is_active'             => 'nullable|boolean',
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
            ->with('success', app()->getLocale() === 'ar' ? 'تم إضافة المستخدم بنجاح' : 'User created successfully');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $branches = Branch::active()->orderBy('name')->get();
        $roles = ['admin', 'branch_user', 'customer'];
        $currentRole = $user->getRoleNames()->first();

        return view('users.edit', compact('user', 'branches', 'roles', 'currentRole'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'  => 'nullable|string|min:8|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
            'role'      => 'required|in:admin,branch_user,customer',
            'locale'    => 'nullable|in:ar,en',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'branch_id' => $request->branch_id,
            'locale'    => $request->locale ?? 'ar',
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync role
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', app()->getLocale() === 'ar' ? 'تم تحديث المستخدم بنجاح' : 'User updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', app()->getLocale() === 'ar'
                ? 'لا يمكنك حذف حسابك الخاص'
                : 'You cannot delete your own account');
        }

        $user->delete();

        return back()->with('success', app()->getLocale() === 'ar' ? 'تم حذف المستخدم' : 'User deleted');
    }
}
