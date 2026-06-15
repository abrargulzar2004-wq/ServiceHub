@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('admin_content')
<h2 style="margin-bottom: 2rem; color: var(--primary);">Dashboard Overview</h2>

<div class="grid grid-cols-4" style="margin-bottom: 2rem;">
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <h3 style="color: var(--text-muted); font-size: 1rem;">Total Users</h3>
        <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">{{ $totalUsers }}</p>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <h3 style="color: var(--text-muted); font-size: 1rem;">Total Workers</h3>
        <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">{{ $totalWorkers }}</p>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <h3 style="color: var(--text-muted); font-size: 1rem;">Services</h3>
        <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">{{ $totalServices }}</p>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <h3 style="color: var(--text-muted); font-size: 1rem;">Total Bookings</h3>
        <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">{{ $totalBookings }}</p>
    </div>
</div>

<div class="grid grid-cols-5" style="margin-bottom: 3rem;">
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <h3 style="color: var(--text-muted); font-size: 1rem;">Completed</h3>
        <p style="font-size: 2rem; font-weight: 700; color: #10B981;">{{ $completedBookings }}</p>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <h3 style="color: var(--text-muted); font-size: 1rem;">Pending</h3>
        <p style="font-size: 2rem; font-weight: 700; color: #F59E0B;">{{ $pendingBookings }}</p>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <h3 style="color: var(--text-muted); font-size: 1rem;">Cancelled</h3>
        <p style="font-size: 2rem; font-weight: 700; color: #EF4444;">{{ $cancelledBookings }}</p>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <h3 style="color: var(--text-muted); font-size: 1rem;">Reviews</h3>
        <p style="font-size: 2rem; font-weight: 700; color: #8B5CF6;">{{ $totalReviews }}</p>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <h3 style="color: var(--text-muted); font-size: 1rem;">Avg Rating</h3>
        <p style="font-size: 2rem; font-weight: 700; color: #F59E0B;">★ {{ number_format($averageRating, 1) }}</p>
    </div>
</div>

<div class="grid grid-cols-2">
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Monthly Bookings</h3>
        <canvas id="monthlyChart"></canvas>
    </div>
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Service Popularity</h3>
        <canvas id="popularityChart"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Bookings',
                    data: @json($monthlyData),
                    borderColor: '#4F46E5',
                    tension: 0.1,
                    fill: true,
                    backgroundColor: 'rgba(79, 70, 229, 0.1)'
                }]
            }
        });

        const ctxPopularity = document.getElementById('popularityChart').getContext('2d');
        new Chart(ctxPopularity, {
            type: 'pie',
            data: {
                labels: @json($popularityData['labels'] ?? []),
                datasets: [{
                    data: @json($popularityData['data'] ?? []),
                    backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']
                }]
            }
        });
    });
</script>
@endsection
