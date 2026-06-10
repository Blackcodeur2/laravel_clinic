<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['facture_id', 'service_medical_id', 'medicament_id', 'quantite', 'prix_unitaire', 'total'])]
class LigneFacture extends Model
{
    use HasFactory;

    protected $table = 'ligne_factures';

    /**
     * Get the parent invoice.
     */
    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    /**
     * Get the service medical associated.
     */
    public function serviceMedical(): BelongsTo
    {
        return $this->belongsTo(ServiceMedical::class);
    }

    /**
     * Get the medicament associated.
     */
    public function medicament(): BelongsTo
    {
        return $this->belongsTo(Medicament::class);
    }
}
