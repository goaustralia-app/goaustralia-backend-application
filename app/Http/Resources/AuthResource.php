<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->resource['user']),
            'access_token' => $this->resource['access_token'],
            'is_new_user' => $this->when(
                array_key_exists('is_new_user', $this->resource),
                fn () => $this->resource['is_new_user']
            ),
        ];
    }
}
