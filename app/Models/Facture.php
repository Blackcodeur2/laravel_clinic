<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['consultation_id', 'numero_facture', 'date_facture', 'montant_total', 'montant_paye', 'reste_a_payer', 'statut'])]
class Facture extends Model
{
    use HasFactory;

    /**
     * Get the consultation.
     */
    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    /**
     * Get the invoice lines.
     */
    public function ligneFactures(): HasMany
    {
        return $this->hasMany(LigneFacture::class);
    }

    /**
     * Get the payments registered for this invoice.
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
