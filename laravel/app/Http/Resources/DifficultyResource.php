<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DifficultyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->dif_id,
            'distance' => [
                'min' => $this->dif_dist_min,
                'max' => $this->dif_dist_max,
                'label' => $this->dif_dist_min . ' - ' . $this->dif_dist_max . ' km',
            ],
        ];
    }
}
