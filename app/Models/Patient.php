<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom', 'prenom', 'date_naissance', 'sexe', 'telephone', 'adresse'])]
class Patient extends Model
{
    use HasFactory;

    /**
     * Get consultations for this patient.
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }
}
