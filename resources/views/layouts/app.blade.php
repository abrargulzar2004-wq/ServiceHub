<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ServiceHub') - Local Service Booking</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <nav class="navbar">
        <a href="{{ route('home') }}" class="nav-brand">
            <span>🛠️ ServiceHub</span>
        </a>
        <ul class="nav-links">
            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}" class="btn btn-primary">Register</a></li>
            @endguest
            @auth
                @if(is_null(auth()->user()->otp_verified_at))
                    <li><a href="{{ route('otp.verify') }}">Verify OTP</a></li>
                @else
                    @php
                        $unreadCount = auth()->user()->notifications()->where('is_read', 0)->count();
                    @endphp
                    <li>
                        <a href="{{ route('notifications.index') }}" style="display: flex; align-items: center; gap: 0.25rem;">
                            Notifications
                            @if($unreadCount > 0)
                                <span style="background: #EF4444; color: white; border-radius: 50%; padding: 0.1rem 0.4rem; font-size: 0.75rem; font-weight: bold;">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @elseif(auth()->user()->role === 'worker')
                        <li><a href="{{ route('worker.dashboard') }}">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                    @endif
                @endif
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="padding: 0.4rem 1rem;">Logout</button>
                    </form>
                </li>
            @endauth
        </ul>
    </nav>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @yield('content')
    </main>

</body>
</html>
