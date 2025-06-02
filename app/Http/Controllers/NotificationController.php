<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends TranslatableController
{
    protected $translatableFields = [
        'title' => ['required', 'string', 'max:255'],
        'body' => ['required', 'string'],
        'data' => ['nullable', 'string'],
    ];

    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'notifiable_type' => 'required|string',
            'notifiable_id' => 'required|integer',
        ]);

        try {
            $notification = Notification::create([
                'type' => $request->type,
                'notifiable_type' => $request->notifiable_type,
                'notifiable_id' => $request->notifiable_id,
                'read_at' => null,
            ]);

            $this->handleTranslations($notification, $request, array_keys($this->translatableFields));

            return response()->json([
                'success' => true,
                'message' => __('messages.notification_created_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.notification_create_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead(Notification $notification)
    {
        try {
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => __('messages.notification_marked_as_read')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.notification_mark_read_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            auth()->user()->unreadNotifications()->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => __('messages.all_notifications_marked_as_read')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.notifications_mark_read_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Notification $notification)
    {
        try {
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => __('messages.notification_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.notification_delete_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyAll()
    {
        try {
            auth()->user()->notifications()->delete();

            return response()->json([
                'success' => true,
                'message' => __('messages.all_notifications_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.notifications_delete_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
