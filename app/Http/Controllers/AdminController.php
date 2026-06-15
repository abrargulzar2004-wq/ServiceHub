<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::where('role', 'customer')->count();
        $totalWorkers = User::where('role', 'worker')->count();
        $totalServices = Service::count();
        $totalBookings = Booking::count();
        
        $completedBookings = Booking::where('status', 'Completed')->count();
        $cancelledBookings = Booking::where('status', 'Cancelled')->count();
        $pendingBookings = Booking::where('status', 'Pending')->count();
        
        $totalReviews = Review::count();
        $averageRating = Review::avg('rating') ?? 0;

        // Monthly bookings chart data
        $bookings = Booking::selectRaw('MONTH(booking_date) as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')->toArray();
            
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $bookings[$i] ?? 0;
        }

        // Service popularity chart data
        $categories = Service::select('category')->distinct()->pluck('category');
        $popularityData = [];
        foreach ($categories as $category) {
            $count = Booking::whereHas('service', function($q) use ($category) {
                $q->where('category', $category);
            })->count();
            $popularityData['labels'][] = $category;
            $popularityData['data'][] = $count;
        }

        return view('admin.dashboard', compact(
            'totalUsers', 'totalWorkers', 'totalServices', 'totalBookings',
            'completedBookings', 'cancelledBookings', 'pendingBookings',
            'totalReviews', 'averageRating',
            'monthlyData', 'popularityData'
        ));
    }

    public function customers(Request $request)
    {
        $query = User::where('role', 'customer');
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        $customers = $query->latest()->paginate(10);
        return view('admin.customers', compact('customers'));
    }

    public function workers(Request $request)
    {
        $query = User::where('role', 'worker');
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        $workers = $query->latest()->paginate(10);
        return view('admin.workers', compact('workers'));
    }

    public function createWorker()
    {
        return view('admin.workers.create');
    }

    public function storeWorker(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'worker',
            'otp_verified_at' => now(),
            'is_active' => true
        ]);

        return redirect()->route('admin.workers')->with('success', 'Worker created successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));
        return back()->with('success', 'User updated successfully.');
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend yourself.');
        }
        
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'suspended';
        return back()->with('success', "User has been $status.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function services(Request $request)
    {
        $query = Service::with('worker');
        if ($search = $request->input('search')) {
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhereHas('worker', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
        }
        $services = $query->latest()->paginate(10);
        return view('admin.services', compact('services'));
    }

    public function createService()
    {
        $workers = User::where('role', 'worker')->get();
        return view('admin.services.create', compact('workers'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'worker_id' => 'required|exists:users,id',
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services')->with('success', 'Service created successfully.');
    }

    public function editService(Service $service)
    {
        $workers = User::where('role', 'worker')->get();
        return view('admin.services.edit', compact('service', 'workers'));
    }

    public function updateService(Request $request, Service $service)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'worker_id' => 'required|exists:users,id',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services')->with('success', 'Service updated successfully.');
    }

    public function destroyService(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted successfully.');
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['service.worker', 'customer']);
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->input('search')) {
            $query->whereHas('service', function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            })->orWhereHas('customer', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }
        $bookings = $query->latest()->paginate(10);
        return view('admin.bookings', compact('bookings'));
    }

    public function reviews(Request $request)
    {
        $query = Review::with(['booking.service', 'booking.customer']);
        if ($search = $request->input('search')) {
            $query->where('comment', 'LIKE', "%{$search}%");
        }
        $reviews = $query->latest()->paginate(10);
        return view('admin.reviews', compact('reviews'));
    }

    public function destroyReview(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }
}
