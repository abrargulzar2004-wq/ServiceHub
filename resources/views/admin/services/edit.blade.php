@extends('layouts.admin')
@section('title', 'Edit Service')
@section('admin_content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="color: var(--primary); margin-bottom: 1.5rem;">Edit Service: {{ $service->title }}</h2>
    <form action="{{ route('admin.services.update', $service) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Service Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $service->title) }}" required>
            @error('title') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category" class="form-control" required>
                @php $categories = ['Plumbing', 'Electrical', 'Cleaning', 'Carpentry', 'Painting', 'Other']; @endphp
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ old('category', $service->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>
            @error('category') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Assign to Worker</label>
            <select name="worker_id" class="form-control" required>
                <option value="">Select Worker</option>
                @foreach($workers as $worker)
                    <option value="{{ $worker->id }}" {{ old('worker_id', $service->worker_id) == $worker->id ? 'selected' : '' }}>
                        {{ $worker->name }} ({{ $worker->email }})
                    </option>
                @endforeach
            </select>
            @error('worker_id') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Price ($)</label>
            <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price', $service->price) }}" required>
            @error('price') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $service->description) }}</textarea>
            @error('description') <span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span> @enderror
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary flex-1">Update Service</button>
            <a href="{{ route('admin.services') }}" class="btn btn-outline flex-1" style="text-align: center;">Cancel</a>
        </div>
    </form>
</div>
@endsection
