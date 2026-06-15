@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div style="max-width: 600px; margin: 2rem auto;">
    <div class="card">
        <h2 style="margin-bottom: 1.5rem; color: var(--primary);">My Profile</h2>
        
        @if(session('success'))
            <div style="padding: 1rem; margin-bottom: 1rem; background: #D1FAE5; color: #065F46; border-radius: var(--radius);">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')<span style="color: red; font-size: 0.8rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email')<span style="color: red; font-size: 0.8rem;">{{ $message }}</span>@enderror
            </div>

            <div style="margin: 2rem 0 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">Change Password (Optional)</h3>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave blank to keep current">
                    @error('password')<span style="color: red; font-size: 0.8rem;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Save Changes</button>
        </form>
    </div>
</div>
@endsection
