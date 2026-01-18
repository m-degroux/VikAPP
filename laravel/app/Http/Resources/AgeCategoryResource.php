<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgeCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->age_id,
            'age_min' => $this->age_min,
            'age_max' => $this->age_max,
            'label' => $this->getLabel(),
        ];
    }

    /**
     * Génère un libellé lisible pour l'UI
     */
    private function getLabel()
    {
        if (is_null($this->age_max)) {
            return "À partir de {$this->age_min} ans";
        }

        return "Entre {$this->age_min} et {$this->age_max} ans";
    }
}
