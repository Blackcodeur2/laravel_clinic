<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicamentRequest;
use App\Http\Resources\MedicamentResource;
use App\Models\Medicament;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class MedicamentApiController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Medicament::class);

        return MedicamentResource::collection(Medicament::latest()->get());
    }

    public function store(MedicamentRequest $request): JsonResponse
    {
        Gate::authorize('create', Medicament::class);
        $medicament = Medicament::create($request->validated());

        return (new MedicamentResource($medicament))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): MedicamentResource
    {
        $medicament = Medicament::findOrFail($id);
        Gate::authorize('view', $medicament);

        return new MedicamentResource($medicament);
    }

    public function update(MedicamentRequest $request, int $id): MedicamentResource
    {
        $medicament = Medicament::findOrFail($id);
        Gate::authorize('update', $medicament);
        $medicament->update($request->validated());

        return new MedicamentResource($medicament->fresh());
    }

    public function destroy(int $id): JsonResponse
    {
        $medicament = Medicament::findOrFail($id);
        Gate::authorize('delete', $medicament);
        $medicament->delete();

        return response()->json(['message' => 'Médicament supprimé avec succès.']);
    }
}
