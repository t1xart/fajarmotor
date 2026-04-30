<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /** GET /api/v1/profile */
    public function show(Request $request)
    {
        $user = $request->user()->load('branch');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
            'role' => $user->getRoleNames()->first(),
            'branch' => $user->branch?->only('id', 'name'),
            'is_active' => $user->is_active,
            'created_at' => $user->created_at?->toDateString(),
        ]);
    }

    /** PUT /api/v1/profile */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:users,email,'.$user->id,
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        ]);
    }

    /** POST /api/v1/profile/photo */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return response()->json([
            'message' => 'Foto profil berhasil diperbarui.',
            'avatar' => Storage::url($path),
        ]);
    }

    /** DELETE /api/v1/profile/photo */
    public function deletePhoto(Request $request)
    {
        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return response()->json(['message' => 'Foto profil berhasil dihapus.']);
    }

    /** PUT /api/v1/profile/password */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password saat ini tidak sesuai.'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password berhasil diperbarui.']);
    }

    /** POST /api/v1/profile/token — generate new Sanctum token (web session auth) */
    public function createToken(Request $request)
    {
        $user = $request->user() ?? auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Revoke old tokens with same name to avoid accumulation
        $user->tokens()->where('name', 'profile-api-token')->delete();
        $token = $user->createToken('profile-api-token')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
