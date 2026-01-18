<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->team_id,
            'name' => $this->team_name,
            'picture' => $this->team_picture,
            'results' => [
                'time' => $this->team_time,
                'points' => $this->team_point,
            ],

            'race' => new RaceResource($this->whenLoaded('race')),
            'captain' => new MemberResource($this->whenLoaded('captain')),
            'members' => MemberResource::collection($this->whenLoaded('members')),
            'members_count' => $this->whenCounted('members'),
        ];
    }
}
