<?php

namespace App\Http\Controllers;

use App\Mail\EmailChangeOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show user profile edit form
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /** Dedicated email change page */
    public function emailPage()
    {
        $pendingOtp = DB::table('email_change_otps')
            ->where('user_id', auth()->id())
            ->where('expires_at', '>', now())
            ->first();

        return view('profile.email', compact('pendingOtp'));
    }

    /**
     * Step 1: Request email change — send OTP
     */
    public function requestEmailChange(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email,'.auth()->id(),
        ], ['new_email.unique' => 'Email sudah digunakan akun lain.']);

        $user = auth()->user();
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing OTP for this user
        DB::table('email_change_otps')->where('user_id', $user->id)->delete();

        DB::table('email_change_otps')->insert([
            'user_id' => $user->id,
            'new_email' => $request->new_email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            Mail::to($request->new_email)->send(new EmailChangeOtp($otp, $request->new_email, $user->name));
        } catch (\Exception $e) {
            // Rollback OTP insert if mail fails
            DB::table('email_change_otps')->where('user_id', $user->id)->delete();

            return back()->withErrors(['new_email' => 'Gagal mengirim email OTP. Periksa konfigurasi mail server.']);
        }

        return back()->with('otp_sent', true)->with('pending_email', $request->new_email);
    }

    /**
     * Step 2: Verify OTP and apply email change
     */
    public function verifyEmailOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $user = auth()->user();
        $record = DB::table('email_change_otps')
            ->where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->first();

        if (! $record) {
            return back()
                ->withErrors(['otp' => 'Kode OTP tidak valid.'])
                ->with('otp_sent', true)
                ->with('pending_email', '');
        }

        if (now()->isAfter($record->expires_at)) {
            DB::table('email_change_otps')->where('user_id', $user->id)->delete();

            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta ulang.']);
        }

        $user->update([
            'email' => $record->new_email,
            'email_verified_at' => now(),
        ]);

        DB::table('email_change_otps')->where('user_id', $user->id)->delete();

        return back()->with('success', 'Email berhasil diperbarui ke '.$record->new_email);
    }

    /**
     * Update user profile photo
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format harus JPG atau PNG.',
            'avatar.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $user = auth()->user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Delete user profile photo
     */
    public function deletePhoto()
    {
        $user = auth()->user();

        // Delete avatar file if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
