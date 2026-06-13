<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultationRequest;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Repositories\ConsultationRepositoryInterface;
use App\Services\FactureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ConsultationApiController extends Controller
{
    public function __construct(
        protected ConsultationRepositoryInterface $consultationRepository,
        protected FactureService $factureService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Consultation::class);

        return ConsultationResource::collection($this->consultationRepository->all());
    }

    public function store(ConsultationRequest $request): JsonResponse
    {
        Gate::authorize('create', Consultation::class);

        $consultation = $this->consultationRepository->create($request->validated());

        // Auto create invoice (Facture)
        $this->factureService->createFactureForConsultation($consultation);

        return (new ConsultationResource($consultation->load(['patient', 'medecin', 'facture'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): ConsultationResource
    {
        $consultation = $this->consultationRepository->find($id);
        if (! $consultation) {
            abort(404, 'Consultation non trouvée.');
        }
        Gate::authorize('view', $consultation);

        return new ConsultationResource($consultation);
    }

    public function update(ConsultationRequest $request, int $id): ConsultationResource
    {
        $consultation = $this->consultationRepository->find($id);
        if (! $consultation) {
            abort(404, 'Consultation non trouvée.');
        }
        Gate::authorize('update', $consultation);
        $this->consultationRepository->update($id, $request->validated());

        return new ConsultationResource($consultation->fresh());
    }

    public function destroy(int $id): JsonResponse
    {
        $consultation = $this->consultationRepository->find($id);
        if (! $consultation) {
            abort(404, 'Consultation non trouvée.');
        }
        Gate::authorize('delete', $consultation);
        $this->consultationRepository->delete($id);

        return response()->json(['message' => 'Consultation supprimée avec succès.']);
    }
}
