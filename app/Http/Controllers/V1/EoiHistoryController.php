<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\EoiHistoryResource;
use App\Models\Eoi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EoiHistoryController extends BaseController
{
    public function index(int $id): JsonResponse
    {
        try {
            $eoi = Eoi::where('user_id', Auth::id())->findOrFail($id);

            $histories = $eoi->histories()->latest('changed_at')->get();

            return $this->successResponse(
                'EOI history retrieved successfully.',
                EoiHistoryResource::collection($histories)
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('EOI not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
