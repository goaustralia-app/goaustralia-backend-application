<?php

namespace App\Http\Controllers\V1;

use App\Domains\InvitationRound\Services\InvitationRoundService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvitationRoundController extends BaseController
{
    public function __construct(
        private InvitationRoundService $invitationRoundService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'subclass_id' => $request->get('subclass_id'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
            ];

            $filters = array_filter($filters, fn ($value) => $value !== null);

            $perPage = (int) $request->get('per_page', 20);
            $invitationRounds = $this->invitationRoundService->getAllInvitationRounds($filters, $perPage);

            return $this->successResponse('Invitation rounds retrieved successfully.', $invitationRounds);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $invitationRound = $this->invitationRoundService->getInvitationRoundById($id);

            if (! $invitationRound) {
                return $this->errorResponse('Invitation round not found.', 404);
            }

            return $this->successResponse('Invitation round retrieved successfully.', $invitationRound);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
