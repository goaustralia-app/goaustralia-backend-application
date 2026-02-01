<?php

namespace App\Domains\OccupationList\Services;

use App\Domains\OccupationList\Repositories\OccupationListRepositoryInterface;
use App\Http\Resources\OccupationListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OccupationListService
{
    public function __construct(
        private OccupationListRepositoryInterface $occupationListRepository
    ) {}

    public function getAllOccupationLists(array $filters = [], int $perPage = 50): AnonymousResourceCollection
    {
        $occupations = $this->occupationListRepository->getAll($filters, $perPage);

        return OccupationListResource::collection($occupations);
    }

    public function getOccupationListById(int $id): ?OccupationListResource
    {
        $occupation = $this->occupationListRepository->findById($id);

        return $occupation ? new OccupationListResource($occupation) : null;
    }

    public function searchOccupationLists(string $query): AnonymousResourceCollection
    {
        $occupations = $this->occupationListRepository->search($query);

        return OccupationListResource::collection($occupations);
    }

    public function getOccupationListsByFilters(array $filters): AnonymousResourceCollection
    {
        $occupations = $this->occupationListRepository->findByFilters($filters);

        return OccupationListResource::collection($occupations);
    }
}
