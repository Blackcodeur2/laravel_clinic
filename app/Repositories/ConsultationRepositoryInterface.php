<?php

namespace App\Repositories;

use App\Models\Consultation;
use Illuminate\Database\Eloquent\Collection;

interface ConsultationRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int $id): ?Consultation;

    public function create(array $data): Consultation;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
