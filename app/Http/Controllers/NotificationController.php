<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications;

        return response()->json([
            'status' => 200,
            'message' => 'All notifications retrieved successfully',
            'data' => $notifications
        ]);
    }

    public function unread(Request $request)
    {
        $notifications = $request->user()->unreadNotifications;

        return response()->json([
            'status' => 200,
            'message' => 'Unread notifications retrieved successfully',
            'data' => $notifications
        ]);
    }

    public function markAsRead($id, Request $request)
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json([
                'status' => 404,
                'message' => 'Notification not found'
            ]);
        }

        $notification->markAsRead();

        return response()->json([
            'status' => 200,
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'status' => 200,
            'message' => 'All notifications marked as read'
        ]);
    }
}
