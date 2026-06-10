<?php

namespace App\Repositories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;

class PatientRepository implements PatientRepositoryInterface
{
    public function all(): Collection
    {
        return Patient::latest()->get();
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
        return Patient::where('nom', 'like', "%{$term}%")
            ->orWhere('prenom', 'like', "%{$term}%")
            ->orWhere('telephone', 'like', "%{$term}%")
            ->latest()
            ->get();
    }
}
