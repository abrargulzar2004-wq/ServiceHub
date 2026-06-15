@extends('layouts.admin')
@section('title', 'Manage Customers')
@section('admin_content')
<div class="flex justify-between items-center mb-6">
    <h2 style="color: var(--primary);">Manage Customers</h2>
    <form action="{{ route('admin.customers') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="input-field" style="width: 250px;">
        <button type="submit" class="btn btn-primary">Search</button>
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
            @forelse($customers as $customer)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $customer->id }}</td>
                <td style="padding: 10px;">{{ $customer->name }}</td>
                <td style="padding: 10px;">{{ $customer->email }}</td>
                <td style="padding: 10px;">
                    @if($customer->is_active)
                        <span style="color: #10B981; font-weight: bold;">Active</span>
                    @else
                        <span style="color: #EF4444; font-weight: bold;">Suspended</span>
                    @endif
                </td>
                <td style="padding: 10px;">{{ $customer->created_at->format('M d, Y') }}</td>
                <td style="padding: 10px; display: flex; gap: 5px;">
                    <form action="{{ route('admin.users.toggle', $customer) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">
                            {{ $customer->is_active ? 'Suspend' : 'Activate' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.users.destroy', $customer) }}" method="POST" onsubmit="return confirm('Delete this customer?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 10px; text-align: center;">No customers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 1rem;">
        {{ $customers->links() }}
    </div>
</div>
@endsection
