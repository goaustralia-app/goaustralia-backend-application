<?php

namespace App\Http\Controllers\V1;

use App\Domains\StatesSkilledOccupationList\Services\StatesSkilledOccupationListService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatesSkilledOccupationListController extends BaseController
{
    public function __construct(
        private StatesSkilledOccupationListService $statesSkilledOccupationListService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'state_id' => $request->get('state_id'),
                'state' => $request->get('state'),
                'subclass_id' => $request->get('subclass_id'),
                'category' => $request->get('category'),
                'financial_year' => $request->get('financial_year'),
                'search' => $request->get('search'),
            ];

            $filters = array_filter($filters, fn ($value) => $value !== null);

            $perPage = (int) $request->get('per_page', 50);
            $occupationLists = $this->statesSkilledOccupationListService->getAllOccupationLists($filters, $perPage);

            return $this->successResponse('States skilled occupation lists retrieved successfully.', $occupationLists);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $occupationList = $this->statesSkilledOccupationListService->getOccupationListById($id);

            if (! $occupationList) {
                return $this->errorResponse('States skilled occupation list not found.', 404);
            }

            return $this->successResponse('States skilled occupation list retrieved successfully.', $occupationList);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
