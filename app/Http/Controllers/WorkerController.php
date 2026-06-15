<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class WorkerController extends Controller
{
    public function dashboard()
    {
        $services = auth()->user()->services;
        $bookings = Booking::whereHas('service', function($q) {
            $q->where('worker_id', auth()->id());
        })->with('service', 'customer', 'review')->get();

        $reviews = \App\Models\Review::whereHas('booking.service', function($q) {
            $q->where('worker_id', auth()->id());
        })->with('booking.customer', 'booking.service')->latest()->get();

        return view('worker.dashboard', compact('services', 'bookings', 'reviews'));
    }


    public function updateStatus(Request $request, Booking $booking)
    {
        if ($booking->service->worker_id !== auth()->id()) abort(403);
        
        $request->validate([
            'status' => 'required|in:Accepted,Completed,Rejected'
        ]);

        $booking->update(['status' => $request->status]);

        Notification::create([
            'user_id' => $booking->customer_id,
            'message' => 'Your booking for ' . $booking->service->title . ' was ' . strtolower($request->status) . ' by the worker.',
        ]);

        return back()->with('success', 'Booking status updated.');
    }
}
