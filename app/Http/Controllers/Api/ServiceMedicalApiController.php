<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceMedicalRequest;
use App\Http\Resources\ServiceMedicalResource;
use App\Models\ServiceMedical;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ServiceMedicalApiController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', ServiceMedical::class);
        return ServiceMedicalResource::collection(ServiceMedical::latest()->get());
    }

    public function store(ServiceMedicalRequest $request): JsonResponse
    {
        Gate::authorize('create', ServiceMedical::class);
        $service = ServiceMedical::create($request->validated());
        return (new ServiceMedicalResource($service))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): ServiceMedicalResource
    {
        $service = ServiceMedical::findOrFail($id);
        Gate::authorize('view', $service);
        return new ServiceMedicalResource($service);
    }

    public function update(ServiceMedicalRequest $request, int $id): ServiceMedicalResource
    {
        $service = ServiceMedical::findOrFail($id);
        Gate::authorize('update', $service);
        $service->update($request->validated());
        return new ServiceMedicalResource($service->fresh());
    }

    public function destroy(int $id): JsonResponse
    {
        $service = ServiceMedical::findOrFail($id);
        Gate::authorize('delete', $service);
        $service->delete();
        return response()->json(['message' => 'Service médical supprimé avec succès.']);
    }
}
