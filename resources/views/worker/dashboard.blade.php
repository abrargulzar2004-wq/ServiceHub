@extends('layouts.app')

@section('title', 'Worker Dashboard')

@section('content')
<div class="dashboard-layout">
    <div class="sidebar">
        <h3>Worker Panel</h3>
        <a href="#" class="sidebar-link active">My Services</a>
    </div>
    
    <div class="dashboard-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="color: var(--primary);">My Services</h2>
        </div>

        @if($services->isEmpty())
            <div class="card" style="text-align: center; padding: 3rem;">
                <p>You haven't added any services yet.</p>
            </div>
        @else
            <div class="grid grid-cols-3">
                @foreach($services as $service)
                    <div class="card">
                        <h3 style="margin-bottom: 0.5rem; color: var(--dark);">{{ $service->title }}</h3>
                        <p style="color: var(--text-muted); margin-bottom: 1rem; font-size: 0.9rem;">{{ $service->category }} | ${{ $service->price }}</p>
                        <p style="margin-bottom: 1.5rem;">{{ Str::limit($service->description, 100) }}</p>
                        

                    </div>
                @endforeach
            </div>
        @endif

        <h2 style="color: var(--primary); margin-top: 3rem; margin-bottom: 2rem;">Recent Bookings</h2>
        <div class="card">
            <table style="width: 100%; text-align: left; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border);">
                        <th style="padding: 1rem 0;">Service</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1rem 0;">{{ $booking->service->title }}</td>
                            <td>{{ $booking->customer->name }}</td>
                            <td>{{ $booking->booking_date }}</td>
                            <td><span style="padding: 0.25rem 0.75rem; border-radius: 50px; background: #FEF3C7; color: #92400E; font-size: 0.875rem; font-weight: 500;">{{ $booking->status }}</span></td>
                            <td style="text-align: right;">
                                @if($booking->status === 'Pending')
                                    <form action="{{ route('worker.booking.status', $booking) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" name="status" value="Accepted">
                                        <button type="submit" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Accept</button>
                                    </form>
                                    <form action="{{ route('worker.booking.status', $booking) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" name="status" value="Rejected">
                                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Reject</button>
                                    </form>
                                @elseif($booking->status === 'Accepted')
                                    <form action="{{ route('worker.booking.status', $booking) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" name="status" value="Completed">
                                        <button type="submit" class="btn btn-primary" style="background: var(--secondary); padding: 0.25rem 0.75rem; font-size: 0.875rem;">Mark Completed</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">No bookings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h2 style="color: var(--primary); margin-top: 3rem; margin-bottom: 2rem;">Customer Reviews</h2>
        @if($reviews->isEmpty())
            <div class="card" style="text-align: center; padding: 2rem;">
                <p>No reviews yet.</p>
            </div>
        @else
            <div class="grid grid-cols-2">
                @foreach($reviews as $review)
                    <div class="card">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <h4 style="color: var(--dark); margin: 0;">{{ $review->booking->service->title }}</h4>
                            <span style="color: #F59E0B; font-weight: bold;">★ {{ $review->rating }}/5</span>
                        </div>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">From {{ $review->booking->customer->name }} on {{ $review->created_at->format('M d, Y') }}</p>
                        <p style="font-size: 0.95rem;">"{{ $review->comment }}"</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
