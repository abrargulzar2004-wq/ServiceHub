@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')
<div class="card auth-card" style="text-align: center;">
    <h2 style="margin-bottom: 1rem; color: var(--primary);">Enter OTP</h2>
    <p style="margin-bottom: 1.5rem; color: var(--text-muted);">
        We sent a 6-digit verification code to your email.
    </p>
    
    <form action="{{ route('otp.verify') }}" method="POST">
        @csrf
        <div class="form-group">
            <input type="text" name="otp" class="form-control" placeholder="123456" style="text-align: center; font-size: 1.5rem; letter-spacing: 0.5rem;" required>
            @error('otp') <span style="color: var(--danger); font-size: 0.875rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Verify Login</button>
    </form>
</div>
@endsection
