<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClubResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->club_id,
            'name' => $this->club_name,
            'address' => $this->club_adress,
            'manager' => new MemberResource($this->whenLoaded('manager')),
            'members' => MemberResource::collection($this->whenLoaded('members')),
            'members_count' => $this->whenCounted('members'),
        ];
    }
}
