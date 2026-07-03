<?php

namespace App\Domains\InvitationRound\Services;

use App\Domains\InvitationRound\Repositories\InvitationRoundRepositoryInterface;
use App\Http\Resources\InvitationRoundGroupedResource;
use App\Http\Resources\InvitationRoundShowResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InvitationRoundService
{
    public function __construct(
        private InvitationRoundRepositoryInterface $invitationRoundRepository
    ) {}

    public function getAllInvitationRounds(array $filters = [], int $perPage = 20): AnonymousResourceCollection
    {
        $invitationRounds = $this->invitationRoundRepository->getAll($filters, $perPage);

        return InvitationRoundGroupedResource::collection($invitationRounds);
    }

    public function getInvitationRoundById(int $id): ?InvitationRoundShowResource
    {
        $data = $this->invitationRoundRepository->findById($id);

        return $data ? new InvitationRoundShowResource($data) : null;
    }
}
