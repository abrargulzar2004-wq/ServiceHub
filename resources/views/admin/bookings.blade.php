@extends('layouts.admin')
@section('title', 'Manage Bookings')
@section('admin_content')
<div class="flex justify-between items-center mb-6">
    <h2 style="color: var(--primary);">Manage Bookings</h2>
    <form action="{{ route('admin.bookings') }}" method="GET" class="flex gap-2">
        <select name="status" class="input-field">
            <option value="">All Statuses</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search service or customer..." class="input-field" style="width: 250px;">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>
<div class="card">
    <table style="width: 100%; text-align: left; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Service</th>
                <th style="padding: 10px;">Customer</th>
                <th style="padding: 10px;">Worker</th>
                <th style="padding: 10px;">Date</th>
                <th style="padding: 10px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $booking->id }}</td>
                <td style="padding: 10px;">{{ $booking->service->title ?? 'N/A' }}</td>
                <td style="padding: 10px;">{{ $booking->customer->name ?? 'N/A' }}</td>
                <td style="padding: 10px;">{{ $booking->service->worker->name ?? 'N/A' }}</td>
                <td style="padding: 10px;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y h:i A') }}</td>
                <td style="padding: 10px;">
                    <span class="badge" style="padding: 5px 10px; border-radius: 12px; font-size: 0.8rem; background-color: 
                        @if($booking->status == 'Completed') #D1FAE5; color: #065F46;
                        @elseif($booking->status == 'Pending') #FEF3C7; color: #92400E;
                        @elseif($booking->status == 'Confirmed') #DBEAFE; color: #1E40AF;
                        @else #FEE2E2; color: #991B1B;
                        @endif
                    ">
                        {{ $booking->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 10px; text-align: center;">No bookings found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 1rem;">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
