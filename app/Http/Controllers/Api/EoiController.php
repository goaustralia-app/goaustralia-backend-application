<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitEoiCalculatorRequest;
use App\Http\Resources\EoiCalculatorResultResource;
use App\Http\Resources\EoiQuestionResource;
use App\Models\EoiAnswer;
use App\Models\EoiQuestion;
use App\Models\EoiUserResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function submitCalculator(SubmitEoiCalculatorRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $userId = Auth::id();
            $responses = $request->responses;

            $categoryPoints = [];
            $totalPoints = 0;
            $selectedVisaSubclass = null;
            $questionDetails = [];

            foreach ($responses as $responseData) {
                $answer = EoiAnswer::with('question')->findOrFail($responseData['answer_id']);

                $category = $answer->question->category;
                $points = $answer->points;

                EoiUserResponse::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'eoi_question_id' => $responseData['question_id'],
                    ],
                    [
                        'eoi_answer_id' => $responseData['answer_id'],
                        'points' => $points,
                    ]
                );

                if ($category === 'Visa Selection') {
                    $selectedVisaSubclass = $this->extractVisaSubclass($answer->answer_text);
                } else {
                    if (! isset($categoryPoints[$category])) {
                        $categoryPoints[$category] = 0;
                    }
                    $categoryPoints[$category] += $points;
                    $totalPoints += $points;
                }

                $questionDetails[] = [
                    'category' => $category,
                    'question' => $answer->question->question,
                    'answer' => $answer->answer_text,
                    'points' => $points,
                ];
            }

            $nominationPoints = $this->calculateNominationPoints($selectedVisaSubclass);
            $finalPoints = $totalPoints + $nominationPoints;

            DB::commit();

            $resultData = [
                'selected_visa_subclass' => $selectedVisaSubclass,
                'points_breakdown' => [
                    'categories' => $categoryPoints,
                    'base_points' => $totalPoints,
                    'nomination_points' => $nominationPoints,
                    'final_points' => $finalPoints,
                ],
                'question_details' => $questionDetails,
                'eligibility' => [
                    'meets_minimum' => $finalPoints >= 65,
                    'minimum_required' => 65,
                    'points_difference' => $finalPoints - 65,
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => new EoiCalculatorResultResource($resultData),
                'message' => 'EOI calculator submission processed successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error processing EOI calculator submission: '.$e->getMessage(),
            ], 500);
        }
    }

    private function extractVisaSubclass(string $answerText): ?string
    {
        if (preg_match('/Subclass (\d+)/', $answerText, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function calculateNominationPoints(?string $visaSubclass): int
    {
        return match ($visaSubclass) {
            '190' => 5,
            '491' => 15,
            default => 0,
        };
    }
}
