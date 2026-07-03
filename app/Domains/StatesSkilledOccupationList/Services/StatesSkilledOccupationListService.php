<?php

namespace App\Domains\StatesSkilledOccupationList\Services;

use App\Domains\StatesSkilledOccupationList\Repositories\StatesSkilledOccupationListRepositoryInterface;
use App\Http\Resources\StatesSkilledOccupationListResource;
use App\Http\Resources\StateWithOccupationListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StatesSkilledOccupationListService
{
    public function __construct(
        private StatesSkilledOccupationListRepositoryInterface $repository
    ) {}

    public function getAllOccupationLists(array $filters = [], int $perPage = 50): AnonymousResourceCollection
    {
        $occupations = $this->repository->getAll($filters, $perPage);

        return StateWithOccupationListResource::collection($occupations);
    }

    public function getOccupationListById(int $id): ?StatesSkilledOccupationListResource
    {
        $occupation = $this->repository->findById($id);

        return $occupation ? new StatesSkilledOccupationListResource($occupation) : null;
    }

    public function getOccupationListByState(string $stateIdentifier, array $filters = []): ?StateWithOccupationListResource
    {
        $state = $this->repository->findStateWithOccupations($stateIdentifier, $filters);

        return $state ? new StateWithOccupationListResource($state) : null;
    }
}
