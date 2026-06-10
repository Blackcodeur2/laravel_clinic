<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'consultation_id' => $this->consultation_id,
            'numero_facture' => $this->numero_facture,
            'date_facture' => $this->date_facture,
            'montant_total' => (float) $this->montant_total,
            'montant_paye' => (float) $this->montant_paye,
            'reste_a_payer' => (float) $this->reste_a_payer,
            'statut' => $this->statut,
            'patient' => [
                'id' => $this->consultation->patient->id ?? null,
                'nom' => $this->consultation->patient->nom ?? null,
                'prenom' => $this->consultation->patient->prenom ?? null,
            ],
            'lines' => $this->ligneFactures->map(function ($ligne) {
                return [
                    'id' => $ligne->id,
                    'service_medical_id' => $ligne->service_medical_id,
                    'service_medical' => $ligne->serviceMedical ? $ligne->serviceMedical->nom : null,
                    'medicament_id' => $ligne->medicament_id,
                    'medicament' => $ligne->medicament ? $ligne->medicament->nom : null,
                    'quantite' => (int) $ligne->quantite,
                    'prix_unitaire' => (float) $ligne->prix_unitaire,
                    'total' => (float) $ligne->total,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
