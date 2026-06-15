@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="card auth-card">
    <h2 style="text-align: center; margin-bottom: 1.5rem; color: var(--primary);">Create an Account</h2>
    
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

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

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">I am a...</label>
            <select name="role" class="form-control" required>
                <option value="customer">Customer (Looking for services)</option>
                <option value="worker">Worker (Offering services)</option>
            </select>
            @error('role') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Register</button>
    </form>
    <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
        Already have an account? <a href="{{ route('login') }}">Login here</a>
    </p>
</div>
@endsection
