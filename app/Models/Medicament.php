<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Medicament extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prix',
        'stock',
        'description',
        'date_peremption',
        'stock_alerte',
    ];

    protected $casts = [
        'date_peremption' => 'date',
        'stock_alerte'    => 'integer',
        'stock'           => 'integer',
        'prix'            => 'decimal:2',
    ];

    /**
     * Check if the medication is expired.
     */
    public function isExpired(): bool
    {
        return $this->date_peremption !== null && $this->date_peremption->isPast();
    }

    /**
     * Check if the medication will expire within a given number of days.
     */
    public function isNearExpiration(int $days = 30): bool
    {
        if ($this->date_peremption === null) {
            return false;
        }
        return ! $this->isExpired() && $this->date_peremption->diffInDays(now()) <= $days;
    }

    /**
     * Check if the stock is at or below the alert threshold.
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->stock_alerte;
    }

    /**
     * Get invoice lines that use this medication.
     */
    public function ligneFactures(): HasMany
    {
        return $this->hasMany(LigneFacture::class);
    }
}
