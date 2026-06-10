<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FactureRequest;
use App\Http\Resources\FactureResource;
use App\Models\Facture;
use App\Models\LigneFacture;
use App\Repositories\FactureRepositoryInterface;
use App\Services\FactureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Exception;

class FactureApiController extends Controller
{
    public function __construct(
        protected FactureRepositoryInterface $factureRepository,
        protected FactureService $factureService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Facture::class);
        return FactureResource::collection($this->factureRepository->all());
    }

    public function show(int $id): FactureResource
    {
        $facture = $this->factureRepository->find($id);
        if (!$facture) {
            abort(404, "Facture non trouvée.");
        }
        Gate::authorize('view', $facture);
        return new FactureResource($facture);
    }

    /**
     * Add a line item (Service or Medicament) to an invoice.
     */
    public function addLine(Request $request, int $id): JsonResponse
    {
        $facture = $this->factureRepository->find($id);
        if (!$facture) {
            abort(404, "Facture non trouvée.");
        }
        Gate::authorize('update', $facture);

        $validated = $request->validate([
            'service_medical_id' => ['nullable', 'exists:service_medicals,id'],
            'medicament_id' => ['nullable', 'exists:medicaments,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
        ]);

        if (empty($validated['service_medical_id']) && empty($validated['medicament_id'])) {
            return response()->json(['error' => 'Veuillez spécifier soit un service médical, soit un médicament.'], 422);
        }

        try {
            $this->factureService->addLigne($facture, $validated);
            return (new FactureResource($facture->fresh()))->response()->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove a line item from an invoice.
     */
    public function removeLine(int $lineId): JsonResponse
    {
        $ligne = LigneFacture::findOrFail($lineId);
        $facture = $ligne->facture;
        Gate::authorize('update', $facture);

        $this->factureService->removeLigne($ligne);
        return response()->json([
            'message' => 'Ligne de facture supprimée avec succès.',
            'facture' => new FactureResource($facture->fresh())
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $facture = $this->factureRepository->find($id);
        if (!$facture) {
            abort(404, "Facture non trouvée.");
        }
        Gate::authorize('delete', $facture);
        $this->factureRepository->delete($id);
        return response()->json(['message' => 'Facture supprimée avec succès.']);
    }
}
