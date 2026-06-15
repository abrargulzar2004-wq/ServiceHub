<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Notification;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $bookings = auth()->user()->bookings()->with('service', 'review')->get();
        return view('customer.dashboard', compact('bookings'));
    }

    public function services(Request $request)
    {
        $query = Service::with('worker');
        
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }
        
        $services = $query->latest()->paginate(12);
        $categories = Service::select('category')->distinct()->pluck('category');
        
        return view('customer.services', compact('services', 'categories'));
    }

    public function book(Request $request, Service $service)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
        ]);

        auth()->user()->bookings()->create([
            'service_id' => $service->id,
            'booking_date' => $request->booking_date,
            'status' => 'Pending'
        ]);

        Notification::create([
            'user_id' => $service->worker_id,
            'message' => 'New booking received for your service: ' . $service->title,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Service booked successfully!');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->customer_id !== auth()->id()) abort(403);
        
        if (!in_array($booking->status, ['Pending', 'Accepted'])) {
            return back()->with('error', 'You can only cancel Pending or Accepted bookings.');
        }

        $booking->update(['status' => 'Cancelled']);

        Notification::create([
            'user_id' => $booking->service->worker_id,
            'message' => 'A customer cancelled their booking for: ' . $booking->service->title,
        ]);
        
        return back()->with('success', 'Booking cancelled.');
    }

    public function review(Request $request, Booking $booking)
    {
        if ($booking->customer_id !== auth()->id()) abort(403);

        if ($booking->status !== 'Completed') {
            return back()->with('error', 'You can only review Completed bookings.');
        }

        if ($booking->review()->exists()) {
            return back()->with('error', 'You have already reviewed this booking.');
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);

        $booking->review()->create([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        Notification::create([
            'user_id' => $booking->service->worker_id,
            'message' => 'A customer left a ' . $request->rating . '-star review for your service: ' . $booking->service->title,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
