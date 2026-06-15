@extends('layouts.admin')
@section('title', 'Manage Services')
@section('admin_content')
<div class="flex justify-between items-center mb-6">
    <h2 style="color: var(--primary);">Manage Services</h2>
    <form action="{{ route('admin.services') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, category, worker..." class="input-field" style="width: 300px;">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary" style="background: var(--secondary); margin-left: 1rem;">+ Add Service</a>
    </form>
</div>
<div class="card">
    <table style="width: 100%; text-align: left; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Title</th>
                <th style="padding: 10px;">Category</th>
                <th style="padding: 10px;">Worker</th>
                <th style="padding: 10px;">Price</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $service->id }}</td>
                <td style="padding: 10px;">{{ $service->title }}</td>
                <td style="padding: 10px;">{{ $service->category }}</td>
                <td style="padding: 10px;">{{ $service->worker->name ?? 'N/A' }}</td>
                <td style="padding: 10px;">${{ number_format($service->price, 2) }}</td>
                <td style="padding: 10px; display: flex; gap: 5px;">
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">Edit</a>
                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Delete this service?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 10px; text-align: center;">No services found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 1rem;">
        {{ $services->links() }}
    </div>
</div>
@endsection
