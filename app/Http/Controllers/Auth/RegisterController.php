<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function show()
    {
        $roles = Role::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();

        return view('auth.register', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'branch_id' => 'nullable|exists:branches,id',
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.exists' => 'Role tidak valid.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'branch_id' => $request->branch_id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users')->with('success', "Akun {$user->name} berhasil dibuat.");
    }

    public function users()
    {
        $users = User::with('roles', 'branch')->latest()->get();

        return view('auth.users', compact('users'));
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Tidak dapat menonaktifkan akun sendiri.']);
        }
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', "Status akun {$user->name} diperbarui.");
    }
}
