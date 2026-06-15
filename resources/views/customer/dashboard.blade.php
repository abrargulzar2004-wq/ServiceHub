@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="dashboard-layout">
    <div class="sidebar">
        <h3>Customer Panel</h3>
        <a href="#" class="sidebar-link active">My Bookings</a>
    </div>
    
    <div class="dashboard-content">
        <h2 style="margin-bottom: 2rem; color: var(--primary);">My Bookings</h2>

        @if($bookings->isEmpty())
            <div class="card" style="text-align: center; padding: 3rem;">
                <p style="margin-bottom: 1rem;">You don't have any bookings yet.</p>
                <a href="{{ route('home') }}#services" class="btn btn-primary">Find Services</a>
            </div>
        @else
            <div class="grid grid-cols-2">
                @foreach($bookings as $booking)
                    <div class="card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <h3 style="color: var(--dark);">{{ $booking->service->title }}</h3>
                            @php
                                $statusColor = '#E0E7FF';
                                $textColor = '#3730A3';
                                $statusText = $booking->status;
                                if ($booking->status === 'Cancelled') {
                                    $statusColor = '#FEE2E2';
                                    $textColor = '#991B1B';
                                    $statusText = 'Cancelled by You';
                                } elseif ($booking->status === 'Rejected') {
                                    $statusColor = '#FEE2E2';
                                    $textColor = '#991B1B';
                                    $statusText = 'Rejected by Worker';
                                } elseif ($booking->status === 'Completed') {
                                    $statusColor = '#D1FAE5';
                                    $textColor = '#065F46';
                                }
                            @endphp
                            <span style="padding: 0.25rem 0.75rem; border-radius: 50px; background: {{ $statusColor }}; color: {{ $textColor }}; font-size: 0.875rem; font-weight: 500;">{{ $statusText }}</span>
                        </div>
                        <p style="color: var(--text-muted); margin-bottom: 0.5rem;">Date: {{ $booking->booking_date }}</p>
                        <p style="margin-bottom: 1.5rem; font-weight: 600;">Total: ${{ $booking->service->price }}</p>
                        
                        <div style="display: flex; gap: 0.5rem;">
                            @if($booking->status === 'Completed' && !$booking->review)
                                <button class="btn btn-primary btn-block" onclick="document.getElementById('review-form-{{ $booking->id }}').style.display='block'">Leave Review</button>
                            @endif

                            @if($booking->status === 'Pending' || $booking->status === 'Accepted')
                                <form action="{{ route('customer.booking.cancel', $booking) }}" method="POST" style="width: 100%;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Cancel this booking?')">Cancel</button>
                                </form>
                            @endif
                        </div>
                        
                        @if($booking->status === 'Completed' && !$booking->review)
                        <div id="review-form-{{ $booking->id }}" style="display: none; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                            <form action="{{ route('customer.review', $booking) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Rating (1-5)</label>
                                    <input type="number" name="rating" min="1" max="5" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Comment</label>
                                    <textarea name="comment" class="form-control" rows="2" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Submit Review</button>
                            </form>
                        </div>
                        @endif

                        @if($booking->review)
                            <div style="margin-top: 1rem; padding: 1rem; background: var(--light); border-radius: var(--radius);">
                                <p style="font-weight: 600; color: #F59E0B;">★ {{ $booking->review->rating }}/5</p>
                                <p style="font-size: 0.9rem; margin-top: 0.25rem;">{{ $booking->review->comment }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
