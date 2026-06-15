<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $unread = $user->notifications()->where('is_read', 0)->latest()->get();
        $history = $user->notifications()->where('is_read', 1)->latest()->take(20)->get();

        // Mark as read after fetching
        $user->notifications()->where('is_read', 0)->update(['is_read' => 1]);

        return view('notifications.index', compact('unread', 'history'));
    }
}
