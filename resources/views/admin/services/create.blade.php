@extends('layouts.admin')
@section('title', 'Add New Service')
@section('admin_content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem;">Add New Service</h2>
    <form action="{{ route('admin.services.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Service Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            @error('title') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category" class="form-control" required>
                <option value="">Select Category</option>
                <option value="Plumbing">Plumbing</option>
                <option value="Electrical">Electrical</option>
                <option value="Cleaning">Cleaning</option>
                <option value="Carpentry">Carpentry</option>
                <option value="Painting">Painting</option>
                <option value="Other">Other</option>
            </select>
            @error('category') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Assign to Worker</label>
            <select name="worker_id" class="form-control" required>
                <option value="">Select Worker</option>
                @foreach($workers as $worker)
                    <option value="{{ $worker->id }}">{{ $worker->name }} ({{ $worker->email }})</option>
                @endforeach
            </select>
            @error('worker_id') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Price ($)</label>
            <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price') }}" required>
            @error('price') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
            @error('description') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary flex-1">Create Service</button>
            <a href="{{ route('admin.services') }}" class="btn btn-outline flex-1" style="text-align: center;">Cancel</a>
        </div>
    </form>
</div>
@endsection
