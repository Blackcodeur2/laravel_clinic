<?php

namespace App\Repositories;

use App\Models\Facture;
use Illuminate\Database\Eloquent\Collection;

class FactureRepository implements FactureRepositoryInterface
{
    public function all(): Collection
    {
        return Facture::with(['consultation.patient', 'consultation.medecin', 'ligneFactures.serviceMedical', 'ligneFactures.medicament', 'paiements'])->latest()->get();
    }

    public function find(int $id): ?Facture
    {
        return Facture::with(['consultation.patient', 'consultation.medecin', 'ligneFactures.serviceMedical', 'ligneFactures.medicament', 'paiements'])->find($id);
    }

    public function create(array $data): Facture
    {
        return Facture::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $facture = Facture::find($id);
        if ($facture) {
            return $facture->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $facture = Facture::find($id);
        if ($facture) {
            return $facture->delete();
        }

        return false;
    }

    public function getUnpaid(): Collection
    {
        return Facture::whereIn('statut', ['IMPAYEE', 'PARTIELLEMENT_PAYEE'])
            ->with(['consultation.patient'])
            ->latest()
            ->get();
    }
}
