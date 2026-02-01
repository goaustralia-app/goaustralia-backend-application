<?php

namespace App\Domains\VisaSubclass\Services;

use App\Domains\VisaSubclass\Repositories\VisaSubclassRepositoryInterface;
use App\Http\Resources\VisaSubclassResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VisaSubclassService
{
    public function __construct(
        private VisaSubclassRepositoryInterface $visaSubclassRepository
    ) {}

    public function getAllVisaSubclasses(array $filters = []): AnonymousResourceCollection
    {
        $visaSubclasses = $this->visaSubclassRepository->getAll($filters);

        return VisaSubclassResource::collection($visaSubclasses);
    }

    public function getVisaSubclassById(int $id): ?VisaSubclassResource
    {
        $visaSubclass = $this->visaSubclassRepository->findById($id);

        return $visaSubclass ? new VisaSubclassResource($visaSubclass) : null;
    }

    public function getVisaSubclassByCode(string $subclassCode): ?VisaSubclassResource
    {
        $visaSubclass = $this->visaSubclassRepository->findBySubclassCode($subclassCode);

        return $visaSubclass ? new VisaSubclassResource($visaSubclass) : null;
    }

    public function searchVisaSubclasses(string $query): AnonymousResourceCollection
    {
        $visaSubclasses = $this->visaSubclassRepository->search($query);

        return VisaSubclassResource::collection($visaSubclasses);
    }

    public function getVisaSubclassesByFilters(array $filters): AnonymousResourceCollection
    {
        $visaSubclasses = $this->visaSubclassRepository->findByFilters($filters);

        return VisaSubclassResource::collection($visaSubclasses);
    }
}
