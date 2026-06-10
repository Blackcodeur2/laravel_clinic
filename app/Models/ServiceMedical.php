<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'nom', 'prix', 'description'])]
class ServiceMedical extends Model
{
    use HasFactory;

    protected $table = 'service_medicals';

    /**
     * Get invoice lines that use this service.
     */
    public function ligneFactures(): HasMany
    {
        return $this->hasMany(LigneFacture::class);
    }
}
