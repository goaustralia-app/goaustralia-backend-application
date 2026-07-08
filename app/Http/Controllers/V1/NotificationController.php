<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\NotificationResource;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends BaseController
{
    public function index(): JsonResponse
    {
        try {
            $notifications = UserNotification::where('user_id', Auth::id())
                ->latest()
                ->paginate(15);

            return $this->successResponse(
                'Notifications retrieved successfully.',
                NotificationResource::collection($notifications)->response()->getData(true)
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function markRead(int $id): JsonResponse
    {
        try {
            $notification = UserNotification::where('user_id', Auth::id())->findOrFail($id);
            $notification->update(['is_read' => true]);

            return $this->successResponse('Notification marked as read.', new NotificationResource($notification));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Notification not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function markAllRead(): JsonResponse
    {
        try {
            UserNotification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return $this->successResponse('All notifications marked as read.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
