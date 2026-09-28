<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        NotificationService::markAllRead(Auth::id());

        $notifications = Notification::where('user_id', Auth::id())->latest()->limit(100)->get();

        return view('trainee.notifications', compact('notifications'));
    }
}
