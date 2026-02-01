<?php

namespace App\Http\Controllers\V1;

use App\Domains\OccupationList\Services\OccupationListService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OccupationListController extends BaseController
{
    public function __construct(
        private OccupationListService $occupationListService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'list' => $request->get('list'),
                'search' => $request->get('search'),
            ];

            $filters = array_filter($filters, fn ($value) => $value !== null);

            $perPage = (int) $request->get('per_page', 50);
            $occupationLists = $this->occupationListService->getAllOccupationLists($filters, $perPage);

            return $this->successResponse('Occupation lists retrieved successfully.', $occupationLists);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $occupationList = $this->occupationListService->getOccupationListById($id);

            if (! $occupationList) {
                return $this->errorResponse('Occupation list not found.', 404);
            }

            return $this->successResponse('Occupation list retrieved successfully.', $occupationList);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
