<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Repositories\PatientRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class PatientApiController extends Controller
{
    public function __construct(
        protected PatientRepositoryInterface $patientRepository
    ) {}

    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Patient::class);

        return PatientResource::collection($this->patientRepository->all());
    }

    public function store(PatientRequest $request): JsonResponse
    {
        Gate::authorize('create', Patient::class);
        $patient = $this->patientRepository->create($request->validated());

        return (new PatientResource($patient))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): PatientResource
    {
        $patient = $this->patientRepository->find($id);
        if (! $patient) {
            abort(404, 'Patient non trouvé.');
        }
        Gate::authorize('view', $patient);

        return new PatientResource($patient);
    }

    public function update(PatientRequest $request, int $id): PatientResource
    {
        $patient = $this->patientRepository->find($id);
        if (! $patient) {
            abort(404, 'Patient non trouvé.');
        }
        Gate::authorize('update', $patient);
        $this->patientRepository->update($id, $request->validated());

        return new PatientResource($patient->fresh());
    }
}
