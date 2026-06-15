@extends('layouts.admin')
@section('title', 'Manage Workers')
@section('admin_content')
<div class="flex justify-between items-center mb-6">
    <h2 style="color: var(--primary);">Manage Workers</h2>
    <form action="{{ route('admin.workers') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="input-field" style="width: 250px;">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('admin.workers.create') }}" class="btn btn-primary" style="background: var(--secondary); margin-left: 1rem;">+ Add Worker</a>
    </form>
</div>
<div class="card">
    <table style="width: 100%; text-align: left; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Name</th>
                <th style="padding: 10px;">Email</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Joined</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($workers as $worker)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $worker->id }}</td>
                <td style="padding: 10px;">{{ $worker->name }}</td>
                <td style="padding: 10px;">{{ $worker->email }}</td>
                <td style="padding: 10px;">
                    @if($worker->is_active)
                        <span style="color: #10B981; font-weight: bold;">Active</span>
                    @else
                        <span style="color: #EF4444; font-weight: bold;">Suspended</span>
                    @endif
                </td>
                <td style="padding: 10px;">{{ $worker->created_at->format('M d, Y') }}</td>
                <td style="padding: 10px; display: flex; gap: 5px;">
                    <form action="{{ route('admin.users.toggle', $worker) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">
                            {{ $worker->is_active ? 'Suspend' : 'Activate' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.users.destroy', $worker) }}" method="POST" onsubmit="return confirm('Delete this worker?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 10px; text-align: center;">No workers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 1rem;">
        {{ $workers->links() }}
    </div>
</div>
@endsection
