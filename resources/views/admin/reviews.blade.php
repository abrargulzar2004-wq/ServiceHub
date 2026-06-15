@extends('layouts.admin')
@section('title', 'Manage Reviews')
@section('admin_content')
<div class="flex justify-between items-center mb-6">
    <h2 style="color: var(--primary);">Manage Reviews</h2>
    <form action="{{ route('admin.reviews') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search comments..." class="input-field" style="width: 300px;">
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
                <th style="padding: 10px;">Rating</th>
                <th style="padding: 10px;">Comment</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $review->id }}</td>
                <td style="padding: 10px;">{{ $review->booking->service->title ?? 'N/A' }}</td>
                <td style="padding: 10px;">{{ $review->booking->customer->name ?? 'N/A' }}</td>
                <td style="padding: 10px; color: #F59E0B;">
                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                </td>
                <td style="padding: 10px; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ $review->comment }}
                </td>
                <td style="padding: 10px;">
                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 10px; text-align: center;">No reviews found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 1rem;">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
