<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'medecin_id' => $this->medecin_id,
            'medecin' => [
                'id' => $this->medecin->id ?? null,
                'nom' => $this->medecin->nom ?? null,
                'prenom' => $this->medecin->prenom ?? null,
            ],
            'date_consultation' => $this->date_consultation,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
