@extends('layouts.app')

@section('title', 'Browse Services')

@section('content')
<div class="dashboard-layout">
    <div class="sidebar">
        <h3>Customer Panel</h3>
        <a href="{{ route('customer.dashboard') }}" class="sidebar-link">My Bookings</a>
        <a href="{{ route('customer.services') }}" class="sidebar-link active">Browse Services</a>
    </div>
    
    <div class="dashboard-content">
        <div class="flex justify-between items-center mb-6">
            <h2 style="color: var(--primary);">Available Services</h2>
            <form action="{{ route('customer.services') }}" method="GET" class="flex gap-2">
                <select name="category" class="input-field">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search services..." class="input-field" style="width: 250px;">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <div class="grid grid-cols-3">
            @forelse($services as $service)
                <div class="card" style="text-align: center;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--primary);">{{ $service->title }}</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">{{ $service->category }} | By {{ $service->worker->name }}</p>
                    <p style="margin-bottom: 1.5rem;">{{ Str::limit($service->description, 80) }}</p>
                    <p style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">${{ $service->price }}</p>
                    <form action="{{ route('customer.book', $service) }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        <button type="submit" class="btn btn-primary btn-block">Book Now</button>
                    </form>
                </div>
            @empty
                <div style="grid-column: span 3; text-align: center; padding: 3rem;">
                    <p>No services found matching your criteria.</p>
                </div>
            @endforelse
        </div>
        
        <div style="margin-top: 1rem;">
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection
