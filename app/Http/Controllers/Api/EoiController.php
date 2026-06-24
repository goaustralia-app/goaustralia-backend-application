<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitEoiCalculatorRequest;
use App\Http\Resources\EoiCalculatorResultResource;
use App\Http\Resources\EoiQuestionResource;
use App\Http\Resources\EoiSuggestionsResource;
use App\Models\EoiAnswer;
use App\Models\EoiQuestion;
use App\Models\EoiUserResponse;
use App\Models\PointsCalculator;
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

            if (! $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required to submit EOI calculator responses.',
                ], 401);
            }

            foreach ($request->responses as $responseData) {
                $answer = EoiAnswer::with('question')->findOrFail($responseData['answer_id']);

                PointsCalculator::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'eoi_question_id' => $responseData['question_id'],
                    ],
                    [
                        'eoi_answer_id' => $responseData['answer_id'],
                        'points' => $answer->points,
                    ]
                );
            }

            DB::commit();

            $userResponses = PointsCalculator::with(['question', 'answer'])
                ->where('user_id', $userId)
                ->get();

            return response()->json([
                'success' => true,
                'data' => new EoiCalculatorResultResource($this->buildPointsBreakdown($userResponses)),
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

    public function getPointsBreakdown(): JsonResponse
    {
        try {
            $userId = Auth::id();

            if (! $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required.',
                ], 401);
            }

            $userResponses = PointsCalculator::with(['question', 'answer'])
                ->where('user_id', $userId)
                ->get();

            if ($userResponses->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No submission found. Please complete the points calculator first.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new EoiCalculatorResultResource($this->buildPointsBreakdown($userResponses)),
                'message' => 'Points breakdown retrieved successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving points breakdown: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Build the points breakdown result data from stored user responses.
     *
     * @param  \Illuminate\Support\Collection<int, EoiUserResponse>  $userResponses
     * @return array<string, mixed>
     */
    private function buildPointsBreakdown(\Illuminate\Support\Collection $userResponses): array
    {
        $categoryPoints = [];
        $totalPoints = 0;
        $selectedVisaSubclass = null;
        $questionDetails = [];

        foreach ($userResponses as $response) {
            $category = $response->question->category;
            $points = $response->points;

            if ($category === 'Visa Selection') {
                $selectedVisaSubclass = $this->extractVisaSubclass($response->answer->answer_text);
            } else {
                $categoryPoints[$category] = ($categoryPoints[$category] ?? 0) + $points;
                $totalPoints += $points;
            }

            $questionDetails[] = [
                'category' => $category,
                'question' => $response->question->question,
                'answer' => $response->answer->answer_text,
                'points' => $points,
            ];
        }

        $nominationPoints = $this->calculateNominationPoints($selectedVisaSubclass);
        $finalPoints = $totalPoints + $nominationPoints;

        return [
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

    public function getSuggestions(): JsonResponse
    {
        try {
            $userId = Auth::id();

            if (! $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required to get personalized suggestions.',
                ], 401);
            }

            // Get user's current responses
            $userResponses = EoiUserResponse::with(['question', 'answer'])
                ->where('user_id', $userId)
                ->get();

            if ($userResponses->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No previous submissions found. Please complete the points calculator first.',
                ], 404);
            }

            // Calculate current points
            $currentPoints = $userResponses->sum('points');
            $currentVisaSubclass = $this->getCurrentVisaSubclass($userResponses);
            $nominationPoints = $this->calculateNominationPoints($currentVisaSubclass);
            $totalCurrentPoints = $currentPoints + $nominationPoints;

            // Generate suggestions
            $suggestions = $this->generateSuggestions($userResponses, $currentPoints);
            $alternativeVisaSuggestions = $this->getAlternativeVisaSuggestions($currentVisaSubclass, $currentPoints);

            $suggestionData = [
                'current_status' => [
                    'base_points' => $currentPoints,
                    'nomination_points' => $nominationPoints,
                    'total_points' => $totalCurrentPoints,
                    'visa_subclass' => $currentVisaSubclass,
                    'meets_minimum' => $totalCurrentPoints >= 65,
                ],
                'improvement_suggestions' => $suggestions,
                'alternative_visa_options' => $alternativeVisaSuggestions,
                'priority_recommendations' => $this->getPriorityRecommendations($suggestions),
            ];

            return response()->json([
                'success' => true,
                'data' => new EoiSuggestionsResource($suggestionData),
                'message' => 'Point improvement suggestions generated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating suggestions: '.$e->getMessage(),
            ], 500);
        }
    }

    private function getCurrentVisaSubclass($userResponses): ?string
    {
        $visaResponse = $userResponses->firstWhere('question.category', 'Visa Selection');

        return $visaResponse ? $this->extractVisaSubclass($visaResponse->answer->answer_text) : null;
    }

    private function generateSuggestions($userResponses, $currentPoints): array
    {
        $suggestions = [];

        foreach ($userResponses as $response) {
            $category = $response->question->category;
            $currentAnswer = $response->answer;
            $currentPoints = $response->points;

            // Skip visa selection category
            if ($category === 'Visa Selection') {
                continue;
            }

            // Get all answers for this question
            $allAnswers = EoiAnswer::where('eoi_question_id', $response->question->id)
                ->where('is_active', true)
                ->orderBy('points', 'desc')
                ->get();

            // Find better options
            $betterOptions = $allAnswers->where('points', '>', $currentPoints);

            if ($betterOptions->isNotEmpty()) {
                $bestOption = $betterOptions->first();
                $potentialGain = $bestOption->points - $currentPoints;

                $suggestions[] = [
                    'category' => $category,
                    'question' => $response->question->question,
                    'current_answer' => [
                        'text' => $currentAnswer->answer_text,
                        'points' => $currentPoints,
                    ],
                    'suggested_answer' => [
                        'text' => $bestOption->answer_text,
                        'points' => $bestOption->points,
                        'description' => $bestOption->description,
                    ],
                    'potential_gain' => $potentialGain,
                    'priority' => $this->calculatePriority($category, $potentialGain),
                    'actionable_steps' => $this->getActionableSteps($category, $currentAnswer->answer_text, $bestOption->answer_text),
                ];
            }
        }

        // Sort by priority and potential gain
        return collect($suggestions)
            ->sortByDesc(function ($suggestion) {
                return $suggestion['priority'] * 10 + $suggestion['potential_gain'];
            })
            ->values()
            ->toArray();
    }

    private function calculatePriority(string $category, int $potentialGain): int
    {
        // Priority scoring based on category and potential gain
        $categoryPriority = match ($category) {
            'English Language' => 10,
            'Age' => 1, // Can't improve age
            'Education' => 8,
            'Work Experience (In Australia)' => 9,
            'Work Experience (Outside Australia)' => 7,
            'Professional Year' => 6,
            'Educational Qualification in Australia' => 5,
            'Specialist Education Qualification' => 4,
            'Partner Skills' => 3,
            'Community Language' => 2,
            'Regional Study' => 2,
            default => 1,
        };

        return min(10, $categoryPriority + ($potentialGain > 10 ? 3 : ($potentialGain > 5 ? 2 : 1)));
    }

    private function getActionableSteps(string $category, string $currentAnswer, string $suggestedAnswer): array
    {
        return match ($category) {
            'English Language' => [
                'Take an English proficiency test (IELTS, PTE, TOEFL)',
                'Consider English language courses or tutoring',
                'Practice with online resources and mock tests',
                'Aim for higher band scores in all four skills',
            ],
            'Education' => [
                'Consider pursuing higher education qualifications',
                'Get your overseas qualifications assessed by relevant authorities',
                'Look into postgraduate programs in Australia',
                'Ensure your qualifications are recognized for your nominated occupation',
            ],
            'Work Experience (In Australia)' => [
                'Gain more skilled work experience in Australia',
                'Ensure your work is in your nominated occupation or closely related',
                'Keep detailed employment records and references',
                'Consider changing to a role more aligned with your nominated occupation',
            ],
            'Work Experience (Outside Australia)' => [
                'Document all relevant overseas work experience',
                'Ensure experience is in your nominated occupation or closely related',
                'Get employment references and job descriptions',
                'Have overseas experience properly assessed',
            ],
            'Professional Year' => [
                'Enroll in a Professional Year Program in Australia',
                'Choose a program relevant to your field (Accounting, Engineering, IT)',
                'Complete the program successfully to gain 5 points',
                'Use the program to gain Australian work experience',
            ],
            'Educational Qualification in Australia' => [
                'Consider studying in Australia',
                'Look into courses that meet the Australian study requirement',
                'Ensure the qualification is relevant to your nominated occupation',
                'Study for at least 2 academic years',
            ],
            'Specialist Education Qualification' => [
                'Consider a Masters by research or PhD in STEM fields',
                'Study at an Australian educational institution',
                'Ensure the qualification includes 2+ years of study in relevant fields',
                'Focus on Science, Technology, Engineering, Mathematics, or ICT',
            ],
            'Community Language' => [
                'Obtain NAATI certification for interpreter/translator skills',
                'Study for NAATI Certified Interpreter or Translator test',
                'Practice community language skills',
                'Ensure certification is at paraprofessional level or above',
            ],
            'Regional Study' => [
                'Consider studying in regional Australia or low population growth areas',
                'Meet the Australian study requirement while in eligible areas',
                'Live and study in the region for at least 2 years',
                'Research eligible regional areas for study',
            ],
            default => ['Consult with a migration agent for specific advice'],
        };
    }

    private function getAlternativeVisaSuggestions(string $currentSubclass, int $currentPoints): array
    {
        $alternatives = [];

        // Calculate points for different visa subclasses
        $visaOptions = [
            '189' => ['name' => 'Skilled Independent', 'bonus' => 0],
            '190' => ['name' => 'Skilled Nominated', 'bonus' => 5],
            '491' => ['name' => 'Skilled Work Regional', 'bonus' => 15],
        ];

        foreach ($visaOptions as $subclass => $details) {
            if ($subclass === $currentSubclass) {
                continue;
            }

            $totalPoints = $currentPoints + $details['bonus'];

            $alternatives[] = [
                'subclass' => $subclass,
                'name' => $details['name'],
                'bonus_points' => $details['bonus'],
                'total_points' => $totalPoints,
                'meets_minimum' => $totalPoints >= 65,
                'recommendation' => $this->getVisaRecommendation($subclass, $totalPoints),
            ];
        }

        return $alternatives;
    }

    private function getVisaRecommendation(string $subclass, int $totalPoints): string
    {
        if ($totalPoints < 65) {
            return "Not eligible - requires {$totalPoints} points (minimum 65)";
        }

        return match ($subclass) {
            '189' => $totalPoints >= 85
                ? 'Excellent option - no nomination required, high competitive score'
                : 'Good option - independent visa with no nomination requirements',
            '190' => 'Good option - requires state nomination but adds 5 bonus points',
            '491' => 'Excellent option for regional migration - adds 15 bonus points and pathway to PR',
            default => 'Consider this option based on your circumstances',
        };
    }

    private function getPriorityRecommendations(array $suggestions): array
    {
        $top3 = array_slice($suggestions, 0, 3);

        return [
            'immediate_actions' => array_slice($top3, 0, 1),
            'medium_term_goals' => array_slice($top3, 1, 2),
            'quick_wins' => collect($suggestions)
                ->where('potential_gain', '>=', 5)
                ->where('priority', '>=', 6)
                ->take(2)
                ->values()
                ->toArray(),
        ];
    }
}
