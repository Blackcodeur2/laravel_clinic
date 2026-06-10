<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['consultation_id', 'numero_facture', 'date_facture', 'montant_total', 'montant_paye', 'reste_a_payer', 'statut', 'verification_token'])]
class Facture extends Model
{
    use HasFactory;

    /**
     * Auto-generate a unique verification token on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (self $facture) {
            $facture->verification_token ??= Str::random(48);
        });
    }

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
