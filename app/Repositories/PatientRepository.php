<?php

namespace App\Repositories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;

class PatientRepository implements PatientRepositoryInterface
{
    public function all(array $filters = []): Collection
    {
        $query = Patient::with('consultations.facture')->latest();

        if (! empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function ($q) use ($term) {
                $q->where('nom', 'like', "%{$term}%")
                    ->orWhere('prenom', 'like', "%{$term}%")
                    ->orWhere('telephone', 'like', "%{$term}%");
            });
        }

        if (! empty($filters['sexe'])) {
            $query->where('sexe', $filters['sexe']);
        }

        return $query->get();
    }

    public function find(int $id): ?Patient
    {
        return Patient::find($id);
    }

    public function create(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $patient = Patient::find($id);
        if ($patient) {
            return $patient->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $patient = Patient::find($id);
        if ($patient) {
            return $patient->delete();
        }

        return false;
    }

    public function search(string $term): Collection
    {
        return Patient::with('consultations.facture')
            ->where('nom', 'like', "%{$term}%")
            ->orWhere('prenom', 'like', "%{$term}%")
            ->orWhere('telephone', 'like', "%{$term}%")
            ->latest()
            ->get();
    }
}
