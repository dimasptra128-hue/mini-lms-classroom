<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordOtpMail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang admin!');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak valid.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        // Validasi input form
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Simpan user baru ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => 'student', // Otomatis mendaftar sebagai student
        ]);

        // Otomatis login setelah berhasil daftar
        Auth::login($user);

        // Alihkan langsung ke dashboard kelas
        return redirect()->to('/kelas')->with('success', 'Akun berhasil dibuat!');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->withInput();
        }

        // Generate 6 digit numeric OTP
        $otp = sprintf('%06d', random_int(0, 999999));

        // Save/update OTP hash to password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => '',
                'otp_code' => Hash::make($otp),
                'expired_at' => now()->addMinutes(10),
                'created_at' => now(),
            ]
        );

        // Send email
        Mail::to($email)->send(new ResetPasswordOtpMail($otp));

        // Put email under password reset process in session
        $request->session()->put('reset_email', $email);
        $request->session()->forget('otp_verified'); // reset verification state just in case

        return redirect()->route('password.otp')->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showVerifyOtp(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $email = $request->session()->get('reset_email');
        $resetToken = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetToken) {
            return redirect()->route('password.request');
        }

        if (now()->greaterThan($resetToken->expired_at)) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP kadaluarsa.',
            ]);
        }

        if (!Hash::check($request->otp_code, $resetToken->otp_code)) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP salah.',
            ]);
        }

        // Mark OTP as verified in session
        $request->session()->put('otp_verified', true);

        return redirect()->route('password.reset-form');
    }

    public function resendOtp(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }

        $email = $request->session()->get('reset_email');
        
        // Generate new 6 digit numeric OTP
        $otp = sprintf('%06d', random_int(0, 999999));

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => '',
                'otp_code' => Hash::make($otp),
                'expired_at' => now()->addMinutes(10),
                'created_at' => now(),
            ]
        );

        // Send email
        Mail::to($email)->send(new ResetPasswordOtpMail($otp));

        return redirect()->route('password.otp')->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    public function showResetPassword(Request $request)
    {
        if (!$request->session()->has('reset_email') || !$request->session()->get('otp_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!$request->session()->has('reset_email') || !$request->session()->get('otp_verified')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $request->session()->get('reset_email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Invalidate session
            Auth::logout();
        }

        // Clean up password_reset_tokens
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Clear session keys
        $request->session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('success', 'Kata sandi berhasil diperbarui. Silakan masuk.');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar.');
    }
}
