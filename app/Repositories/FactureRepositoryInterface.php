<?php

namespace App\Repositories;

use App\Models\Facture;
use Illuminate\Database\Eloquent\Collection;

interface FactureRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Facture;
    public function create(array $data): Facture;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getUnpaid(): Collection;
}
