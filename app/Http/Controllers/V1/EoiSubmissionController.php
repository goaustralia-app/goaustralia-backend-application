<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreEoiRequest;
use App\Http\Requests\UpdateEoiRequest;
use App\Http\Resources\EoiResource;
use App\Models\Eoi;
use App\Models\EoiAnswer;
use App\Models\EoiDetail;
use App\Services\EoiHistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EoiSubmissionController extends BaseController
{
    public function __construct(private EoiHistoryService $historyService) {}

    public function index(): JsonResponse
    {
        try {
            $eois = Eoi::with(['subclass', 'details.question', 'details.answer'])
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(15);

            return $this->successResponse(
                'EOIs retrieved successfully.',
                EoiResource::collection($eois)->response()->getData(true)
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $eoi = Eoi::with(['subclass', 'details.question', 'details.answer'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            return $this->successResponse('EOI retrieved successfully.', new EoiResource($eoi));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('EOI not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(StoreEoiRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $totalPoints = $this->calculateTotalPoints($request->responses);

            $eoi = Eoi::create([
                'user_id' => Auth::id(),
                'eoi_number' => $request->eoi_number,
                'submission_date' => $request->submission_date,
                'expiry_date' => $request->expiry_date,
                'state_nomination' => $request->state_nomination,
                'notes' => $request->notes,
                'total_points' => $totalPoints,
                'subclass_id' => $request->subclass_id,
            ]);

            $this->syncDetails($eoi, $request->responses);

            DB::commit();

            $eoi->load(['subclass', 'details.question', 'details.answer']);

            return $this->successResponse('EOI submitted successfully.', new EoiResource($eoi), 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function update(UpdateEoiRequest $request, int $id): JsonResponse
    {
        try {
            $eoi = Eoi::where('user_id', Auth::id())->findOrFail($id);

            DB::beginTransaction();

            $previousData = $eoi->only([
                'eoi_number', 'submission_date', 'expiry_date',
                'state_nomination', 'notes', 'total_points', 'subclass_id',
            ]);

            $totalPoints = $this->calculateTotalPoints($request->responses);

            $eoi->update([
                'eoi_number' => $request->eoi_number,
                'submission_date' => $request->submission_date,
                'expiry_date' => $request->expiry_date,
                'state_nomination' => $request->state_nomination,
                'notes' => $request->notes,
                'total_points' => $totalPoints,
                'subclass_id' => $request->subclass_id,
            ]);

            $eoi->details()->delete();
            $this->syncDetails($eoi, $request->responses);
            $this->historyService->record($eoi, $previousData);

            DB::commit();

            $eoi->load(['subclass', 'details.question', 'details.answer']);

            return $this->successResponse('EOI updated successfully.', new EoiResource($eoi));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('EOI not found.', 404);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $eoi = Eoi::where('user_id', Auth::id())->findOrFail($id);
            $eoi->delete();

            return $this->successResponse('EOI deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('EOI not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * @param  array<int, array{question_id: int, answer_id: int}>  $responses
     */
    private function syncDetails(Eoi $eoi, array $responses): void
    {
        foreach ($responses as $responseData) {
            $answer = EoiAnswer::findOrFail($responseData['answer_id']);

            EoiDetail::create([
                'eoi_id' => $eoi->id,
                'eoi_question_id' => $responseData['question_id'],
                'eoi_answer_id' => $responseData['answer_id'],
                'points' => $answer->points,
            ]);
        }
    }

    /**
     * @param  array<int, array{question_id: int, answer_id: int}>  $responses
     */
    private function calculateTotalPoints(array $responses): int
    {
        $answerIds = array_column($responses, 'answer_id');

        return EoiAnswer::whereIn('id', $answerIds)->sum('points');
    }
}
