<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaiementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'facture_id' => $this->facture_id,
            'numero_facture' => $this->facture->numero_facture ?? null,
            'montant' => (float) $this->montant,
            'mode_paiement' => $this->mode_paiement,
            'reference' => $this->reference,
            'date_paiement' => $this->date_paiement,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
