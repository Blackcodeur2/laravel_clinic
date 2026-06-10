<?php

namespace App\Repositories;

use App\Models\Consultation;
use Illuminate\Database\Eloquent\Collection;

class ConsultationRepository implements ConsultationRepositoryInterface
{
    public function all(array $filters = []): Collection
    {
        $query = Consultation::with(['patient', 'medecin', 'facture'])->latest();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                })->orWhereHas('medecin', function ($mq) use ($search) {
                    $mq->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                });
            });
        }

        if (! empty($filters['medecin_id'])) {
            $query->where('medecin_id', $filters['medecin_id']);
        }

        if (! empty($filters['date_consultation'])) {
            $query->whereDate('date_consultation', $filters['date_consultation']);
        }

        return $query->get();
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
