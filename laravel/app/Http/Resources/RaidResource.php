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
            'raid_id' => $this->raid_id,
            'raid_name' => $this->raid_name,
            'raid_reg_start_date' => $this->raid_reg_start_date,
            'raid_reg_end_date' => $this->raid_reg_end_date,
            'raid_start_date' => $this->raid_start_date,
            'raid_end_date' => $this->raid_end_date,
            'raid_contact' => $this->raid_contact,
            'raid_website' => $this->raid_website,
            'raid_place' => $this->raid_place,
            'raid_picture' => $this->raid_picture,
            'raid_lat' => $this->raid_lat,
            'raid_lng' => $this->raid_lng,
            'club_id' => $this->club_id,
            'races' => $this->whenLoaded('races'),
            'club' => $this->whenLoaded('club'),
        ];
    }
}
