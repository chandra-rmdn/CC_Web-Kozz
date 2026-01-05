<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Generate reset link and return as JSON (for AJAX)
     */
    public function generateResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Check rate limiting (max 3 requests per 15 minutes)
        $recentRequests = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('created_at', '>', Carbon::now()->subMinutes(15))
            ->count();

        if ($recentRequests >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak permintaan. Tunggu 15 menit.'
            ], 429);
        }

        // Generate token
        $token = Str::random(60);

        // Hash token for database
        $hashedToken = Hash::make($token);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $hashedToken,
            'created_at' => Carbon::now()
        ]);

        // Build reset link
        $resetLink = url("/reset-password/{$token}?email=" . urlencode($request->email));

        $expiryTime = Carbon::now()->addMinutes(60)
            ->timezone('Asia/Jakarta')
            ->format('H:i');

        return response()->json([
            'success' => true,
            'link' => $resetLink,
            'token' => $token,
            'expires_at' => $expiryTime,
            'timezone' => 'WIB'
        ]);
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle reset password submission
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Verify token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        // Check token expiry (60 minutes)
        $tokenCreated = Carbon::parse($resetRecord->created_at);
        if ($tokenCreated->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return back()->withErrors(['email' => 'Token sudah kadaluarsa.']);
        }

        // Verify token hash
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Token tidak valid.']);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete used token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            // Redirect to login with success message
            return redirect()->route('password.reset.success')
                ->with('success', 'Password berhasil direset! Silakan login dengan password baru.')
                ->with('email', $request->email);
        }

        return back()->withErrors(['email' => 'Email tidak ditemukan.']);
    }
}