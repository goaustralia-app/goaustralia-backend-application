<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'country' => $this->whenLoaded('country', fn () => $this->country ? [
                'id' => $this->country->id,
                'name' => $this->country->name,
                'code' => $this->country->code,
            ] : null),
            'is_onboard' => $this->is_onboard,
            'is_subscribed' => $this->is_subscribed,
            'is_eoi_signed_up' => $this->is_eoi_signed_up,
            'is_calculated' => $this->is_calculated,
            'provider_name' => $this->provider_name,
            'provider_id' => $this->provider_id,
            'is_social_user' => ! empty($this->provider_name),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
