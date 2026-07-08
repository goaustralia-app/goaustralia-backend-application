<?php

namespace App\Http\Controllers\V1;

use App\Enums\ReminderStatus;
use App\Http\Controllers\BaseController;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReminderController extends BaseController
{
    public function index(): JsonResponse
    {
        try {
            $reminders = Reminder::where('user_id', Auth::id())
                ->latest('reminder_date')
                ->paginate(15);

            return $this->successResponse(
                'Reminders retrieved successfully.',
                ReminderResource::collection($reminders)->response()->getData(true)
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function markRead(int $id): JsonResponse
    {
        try {
            $reminder = Reminder::where('user_id', Auth::id())->findOrFail($id);
            $reminder->update(['status' => ReminderStatus::Dismissed]);

            return $this->successResponse('Reminder dismissed.', new ReminderResource($reminder));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Reminder not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
