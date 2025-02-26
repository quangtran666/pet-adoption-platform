<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Notification::class);

        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return NotificationResource::collection($notifications);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->update([
            'read_at' => now()
        ]);

        return new NotificationResource($notification);
    }

    public function markAllAsRead(Request $request)
    {
        $this->authorize('markAllAsRead', Notification::class);

        auth()->user()
            ->notifications()
            ->whereNull('read_at')
            ->update([
                'read_at' => now()
            ]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}
