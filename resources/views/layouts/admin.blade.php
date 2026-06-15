@extends('layouts.app')

@section('content')
<div class="dashboard-layout">
    <div class="sidebar">
        <h3>Admin Panel</h3>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.customers') }}" class="sidebar-link {{ request()->routeIs('admin.customers') ? 'active' : '' }}">Customers</a>
        <a href="{{ route('admin.workers') }}" class="sidebar-link {{ request()->routeIs('admin.workers') ? 'active' : '' }}">Workers</a>
        <a href="{{ route('admin.services') }}" class="sidebar-link {{ request()->routeIs('admin.services') ? 'active' : '' }}">Services</a>
        <a href="{{ route('admin.bookings') }}" class="sidebar-link {{ request()->routeIs('admin.bookings') ? 'active' : '' }}">Bookings</a>
        <a href="{{ route('admin.reviews') }}" class="sidebar-link {{ request()->routeIs('admin.reviews') ? 'active' : '' }}">Reviews</a>
    </div>
    
    <div class="dashboard-content">
        @yield('admin_content')
    </div>
</div>
@endsection
