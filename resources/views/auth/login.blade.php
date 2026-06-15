@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="card auth-card">
    <h2 style="text-align: center; margin-bottom: 1.5rem; color: var(--primary);">Welcome Back</h2>
    
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Login</button>
    </form>
    <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
        Don't have an account? <a href="{{ route('register') }}">Register here</a>
    </p>
</div>
@endsection
