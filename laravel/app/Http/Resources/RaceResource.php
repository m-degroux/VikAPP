<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->race_id,
            'name' => $this->race_name,
            'duration' => $this->race_duration,
            'length' => $this->race_length,
            'reduction' => $this->race_reduction,
            'start_date' => $this->race_start_date,
            'end_date' => $this->race_end_date,
            'pricing' => [
                'meal_price' => $this->race_meal_price,
            ],
            'constraints' => [
                'min_participants' => $this->race_min_part,
                'max_participants' => $this->race_max_part,
                'min_teams' => $this->race_min_team,
                'max_teams' => $this->race_max_team,
                'max_per_team' => $this->race_max_part_per_team,
            ],
            'raid' => new RaidResource($this->whenLoaded('raid')),
            'type' => new TypeResource($this->whenLoaded('type')),
            'difficulty' => new DifficultyResource($this->whenLoaded('difficulty')),
            'age_categories' => AgeCategoryResource::collection($this->whenLoaded('ageCategories')),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'managers' => MemberResource::collection($this->whenLoaded('managers')),
        ];
    }
}
