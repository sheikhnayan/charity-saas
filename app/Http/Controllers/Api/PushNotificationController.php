<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    protected $notificationService;

    public function __construct(PushNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Save FCM token for current user
     */
    public function saveToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'device_type' => 'sometimes|in:web,android,ios',
            'browser' => 'sometimes|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $tokenRecord = $this->notificationService->registerToken(
                $user->id,
                $request->token,
                $request->input('device_type', 'web'),
                $request->input('browser')
            );

            return response()->json([
                'success' => true,
                'message' => 'Token saved successfully',
                'token_id' => $tokenRecord->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete FCM token
     */
    public function deleteToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $deleted = $this->notificationService->deleteToken($request->token);

            return response()->json([
                'success' => $deleted,
                'message' => $deleted ? 'Token deleted successfully' : 'Token not found'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's registered devices/tokens
     */
    public function getDevices(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Pass null to get all devices for the user across all websites
            $devices = $this->notificationService->getUserTokens($user->id, null);

            return response()->json([
                'success' => true,
                'devices' => $devices
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch devices: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's notification history
     */
    public function getNotifications(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $limit = $request->input('limit', 50);
            $notifications = $this->notificationService->getUserNotifications($user->id, $limit);

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $this->notificationService->getUnreadCount($user->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, int $notificationId): JsonResponse
    {
        try {
            $success = $this->notificationService->markAsRead($notificationId);

            return response()->json([
                'success' => $success,
                'message' => $success ? 'Notification marked as read' : 'Notification not found'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $count = $this->notificationService->getUnreadCount($user->id);

            return response()->json([
                'success' => true,
                'unread_count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test notification (for testing purposes)
     */
    public function testNotification(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $success = $this->notificationService->sendToUser(
                $user->id,
                'Test Notification',
                'This is a test push notification from Fundably',
                ['test' => true, 'url' => url('/admin')],
                'general'
            );

            return response()->json([
                'success' => $success,
                'message' => $success ? 'Test notification sent!' : 'Failed to send test notification'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user notification preferences
     */
    public function getPreferences(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $preferences = $this->notificationService->getPreferences($user->id);

            return response()->json([
                'success' => true,
                'preferences' => $preferences
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user notification preferences
     */
    public function updatePreferences(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $preferences = $this->notificationService->updatePreferences($user->id, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully',
                'preferences' => $preferences
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $count = $this->notificationService->markAllAsRead($user->id);

            return response()->json([
                'success' => true,
                'message' => "{$count} notifications marked as read"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all as read: ' . $e->getMessage()
            ], 500);
        }
    }
}
