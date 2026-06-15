<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:customer,worker',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->otp_verified_at) {
                return back()->withErrors(['email' => 'The email has already been taken.'])->onlyInput('email');
            }
            $user->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
        }

        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp]);

        Mail::to($user->email)->send(new OtpMail($otp));

        $request->session()->put('otp_email', $user->email);
        return redirect()->route('otp.verify')->with('success', 'Registration successful. Please check your email for the OTP.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->is_active) {
                return back()->withErrors(['email' => 'Your account has been suspended. Please contact support.']);
            }

            if (!$user->otp_verified_at) {
                $otp = rand(100000, 999999);
                $user->update(['otp' => $otp]);
                Mail::to($user->email)->send(new OtpMail($otp));

                $request->session()->put('otp_email', $user->email);
                return redirect()->route('otp.verify')->with('error', 'Please verify your email to continue. A new OTP has been sent to your email.');
            }

            Auth::login($user);
            $request->session()->regenerate();
            return $this->redirectBasedOnRole($user)->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showOtp()
    {
        if (!session()->has('otp_email')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $email = $request->session()->get('otp_email');
        if (!$email) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::where('email', $email)->first();

        if ($user && $user->otp == $request->otp) {
            $user->update([
                'otp_verified_at' => now(),
                'otp' => null
            ]);

            Auth::login($user);
            $request->session()->forget('otp_email');
            $request->session()->regenerate();

            return $this->redirectBasedOnRole($user)->with('success', 'OTP verified successfully.');
        }

        return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'worker') {
            return redirect()->route('worker.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
}
