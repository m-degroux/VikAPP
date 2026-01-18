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

            // Le responsable du club (si chargé)
            'manager' => new MemberResource($this->whenLoaded('manager')),

            // La liste des membres appartenant au club (si chargé)
            'members' => MemberResource::collection($this->whenLoaded('members')),

            // Nombre total de membres
            'members_count' => $this->whenCounted('members'),
        ];
    }
}
