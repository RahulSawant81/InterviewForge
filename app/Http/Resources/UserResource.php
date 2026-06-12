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
            'phone' => $this->phone,
            'status' => [
                'value' => $this->status?->value,
                'name' => $this->status?->label(),
            ],
            'role' => $this->role?->name,
            'permissions' => $this->whenLoaded('role', fn () => $this->role?->permissions->pluck('name')->values()),
            'profile' => ProfileResource::make($this->whenLoaded('profile')),
            'created_at' => $this->created_at,
        ];
    }
}
