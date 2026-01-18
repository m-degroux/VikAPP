<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RaidResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->raid_id,
            'name' => $this->raid_name,
            'registration' => [
                'start' => $this->raid_reg_start_date,
                'end' => $this->raid_reg_end_date,
            ],
            'dates' => [
                'start' => $this->raid_start_date,
                'end' => $this->raid_end_date,
            ],
            'contact' => $this->raid_contact,
            'website' => $this->raid_website,
            'location' => [
                'place' => $this->raid_place,
                'lat' => $this->raid_lat,
                'lng' => $this->raid_lng,
            ],
            'picture' => $this->raid_picture,
            'is_ongoing' => $this->isOngoing(),
            'min_age' => $this->minAge(),
            'races_count' => $this->racesCount(),
            'countdown' => $this->timeUntilNextRace(),
            'races' => RaceResource::collection($this->whenLoaded('races')),
            'managers' => MemberResource::collection($this->whenLoaded('managers')),
        ];
    }
}
