<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['facture_id', 'montant', 'mode_paiement', 'reference', 'date_paiement'])]
class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    /**
     * Get the invoice paid by this payment.
     */
    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }
}
