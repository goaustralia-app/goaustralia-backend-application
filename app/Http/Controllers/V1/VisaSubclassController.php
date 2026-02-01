<?php

namespace App\Http\Controllers\V1;

use App\Domains\VisaSubclass\Services\VisaSubclassService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisaSubclassController extends BaseController
{
    public function __construct(
        private VisaSubclassService $visaSubclassService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'is_permanent' => $request->has('is_permanent') ? $request->boolean('is_permanent') : null,
                'points_tested' => $request->has('points_tested') ? $request->boolean('points_tested') : null,
                'stream' => $request->get('stream'),
                'search' => $request->get('search'),
            ];

            $filters = array_filter($filters, fn ($value) => $value !== null);

            $visaSubclasses = $this->visaSubclassService->getAllVisaSubclasses($filters);

            return $this->successResponse('Visa subclasses retrieved successfully.', $visaSubclasses);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $visaSubclass = $this->visaSubclassService->getVisaSubclassById($id);

            if (! $visaSubclass) {
                return $this->errorResponse('Visa subclass not found.', 404);
            }

            return $this->successResponse('Visa subclass retrieved successfully.', $visaSubclass);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
