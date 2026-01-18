<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->user_id,
            'username' => $this->user_username,
            'profile' => [
                'firstname' => $this->mem_firstname,
                'lastname' => $this->mem_name,
                'birthdate' => $this->mem_birthdate,
            ],
            'contact' => [
                'email' => $this->mem_email,
                'phone' => $this->mem_phone,
                'address' => $this->mem_adress,
            ],
            'licence' => [
                'default_number' => $this->mem_default_licence,
            ],

            'club' => new ClubResource($this->whenLoaded('club')),
            'races' => RaceResource::collection($this->whenLoaded('races')),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'managed_raids' => RaidResource::collection($this->whenLoaded('managedRaids')),
            'managed_races' => RaceResource::collection($this->whenLoaded('managedRaces')),
        ];
    }
}
