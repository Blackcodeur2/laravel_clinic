<?php

namespace App\Repositories;

use App\Models\Consultation;
use Illuminate\Database\Eloquent\Collection;

class ConsultationRepository implements ConsultationRepositoryInterface
{
    public function all(): Collection
    {
        return Consultation::with(['patient', 'medecin', 'facture'])->latest()->get();
    }

    public function find(int $id): ?Consultation
    {
        return Consultation::with(['patient', 'medecin', 'facture'])->find($id);
    }

    public function create(array $data): Consultation
    {
        return Consultation::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $consultation = Consultation::find($id);
        if ($consultation) {
            return $consultation->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $consultation = Consultation::find($id);
        if ($consultation) {
            return $consultation->delete();
        }
        return false;
    }
}
