@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="hero">
    <h1>Find the Best Local Services</h1>
    <p>Connect with trusted professionals in your area. Quick, secure, and easy.</p>
    @guest
        <a href="{{ route('register') }}" class="btn btn-primary" style="background: var(--white); color: var(--primary); padding: 1rem 2rem; font-size: 1.1rem; border-radius: 50px;">Get Started Today</a>
    @endguest
    @auth
        <a href="#services" class="btn btn-primary" style="background: var(--white); color: var(--primary); padding: 1rem 2rem; font-size: 1.1rem; border-radius: 50px;">Explore Services</a>
    @endauth
</div>

@auth
<div class="grid grid-cols-3" id="services" style="margin-top: 3rem;">
    @forelse($services as $service)
        <div class="card" style="text-align: center;">
            <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--primary);">{{ $service->title }}</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">{{ $service->category }} | By {{ $service->worker->name }}</p>
            <p style="margin-bottom: 1.5rem;">{{ Str::limit($service->description, 80) }}</p>
            <p style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">${{ $service->price }}</p>
            @if(auth()->user()->role === 'customer')
                <form action="{{ route('customer.book', $service) }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    <button type="submit" class="btn btn-primary btn-block">Book Now</button>
                </form>
            @endif
        </div>
    @empty
        <div style="grid-column: span 3; text-align: center; padding: 3rem;">
            <p>No services available right now. Check back later!</p>
        </div>
    @endforelse
</div>
@endauth

@guest
<div style="text-align: center; margin-top: 4rem; padding: 3rem; background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow);">
    <h2 style="color: var(--dark); margin-bottom: 1rem;">Log in to View Services</h2>
    <p style="color: var(--text-muted); margin-bottom: 0;">Please use the <strong>Login</strong> or <strong>Register</strong> buttons at the top of the page to explore the available local services and make a booking.</p>
</div>
@endguest
@endsection
