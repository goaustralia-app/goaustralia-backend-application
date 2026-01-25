<?php

namespace App\Http\Controllers\V1;

use App\Domains\Country\Services\CountryService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class CountryController extends BaseController
{
    public function __construct(
        private CountryService $countryService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $countries = $this->countryService->getAllCountries();

            return $this->successResponse('Countries retrieved successfully.', $countries);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $country = $this->countryService->getCountryById($id);

            if (! $country) {
                return $this->errorResponse('Country not found.', 404);
            }

            return $this->successResponse('Country retrieved successfully.', $country);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
