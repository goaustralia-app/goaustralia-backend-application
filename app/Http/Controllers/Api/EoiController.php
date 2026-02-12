<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EoiQuestionResource;
use App\Models\EoiQuestion;
use Illuminate\Http\JsonResponse;

class EoiController extends Controller
{
    public function getQuestions(): JsonResponse
    {
        $questions = EoiQuestion::active()
            ->ordered()
            ->with(['answers' => function ($query) {
                $query->active()->ordered();
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => EoiQuestionResource::collection($questions),
            'message' => 'EOI questions retrieved successfully',
        ]);
    }
}
