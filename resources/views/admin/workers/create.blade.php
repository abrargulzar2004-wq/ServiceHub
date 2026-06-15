@extends('layouts.admin')
@section('title', 'Add New Worker')
@section('admin_content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem;">Add New Worker</h2>
    <form action="{{ route('admin.workers.store') }}" method="POST">
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
            <input type="password" name="password" class="form-control" required minlength="6">
            @error('password') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary flex-1">Create Worker</button>
            <a href="{{ route('admin.workers') }}" class="btn btn-outline flex-1" style="text-align: center;">Cancel</a>
        </div>
    </form>
</div>
@endsection
