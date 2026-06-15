@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="color: var(--primary);">Notifications</h2>
    </div>

    @if($unread->isNotEmpty())
        <div style="margin-bottom: 3rem;">
            <h3 style="color: var(--dark); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <span style="display: inline-block; width: 10px; height: 10px; background: #EF4444; border-radius: 50%;"></span>
                New Alerts
            </h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($unread as $notification)
                    <div class="card" style="border-left: 4px solid #EF4444;">
                        <p style="margin-bottom: 0.5rem; font-size: 1.1rem; color: var(--dark);">{{ $notification->message }}</p>
                        <p style="color: var(--text-muted); font-size: 0.85rem;">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div>
        <h3 style="color: var(--text-muted); margin-bottom: 1rem; font-size: 1.1rem;">History</h3>
        @if($history->isEmpty() && $unread->isEmpty())
            <div class="card" style="text-align: center; padding: 3rem;">
                <p>You have no notifications.</p>
            </div>
        @elseif($history->isEmpty())
            <p style="color: var(--text-muted);">No read notifications yet.</p>
        @else
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($history as $notification)
                    <div class="card" style="opacity: 0.8; background: var(--light);">
                        <p style="margin-bottom: 0.5rem; color: var(--dark);">{{ $notification->message }}</p>
                        <p style="color: var(--text-muted); font-size: 0.85rem;">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
