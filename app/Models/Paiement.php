<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['facture_id', 'montant', 'mode_paiement', 'reference', 'date_paiement', 'verification_token'])]
class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    /**
     * Auto-generate a unique verification token on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (self $paiement) {
            $paiement->verification_token ??= Str::random(48);
        });
    }

    /**
     * Get the invoice paid by this payment.
     */
    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }
}
