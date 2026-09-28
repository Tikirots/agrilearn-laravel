<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationAjaxController extends Controller
{
    public function markAllRead(): JsonResponse
    {
        NotificationService::markAllRead(Auth::id());

        return response()->json(['ok' => true]);
    }
}
