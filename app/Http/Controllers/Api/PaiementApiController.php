<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaiementRequest;
use App\Http\Resources\PaiementResource;
use App\Models\Paiement;
use App\Models\Facture;
use App\Services\FactureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Exception;

class PaiementApiController extends Controller
{
    public function __construct(
        protected FactureService $factureService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Paiement::class);
        return PaiementResource::collection(Paiement::with('facture.consultation.patient')->latest()->get());
    }

    public function store(PaiementRequest $request): JsonResponse
    {
        Gate::authorize('create', Paiement::class);

        $facture = Facture::findOrFail($request->facture_id);

        try {
            $paiement = $this->factureService->addPaiement($facture, $request->validated());
            return (new PaiementResource($paiement))
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show(int $id): PaiementResource
    {
        $paiement = Paiement::with('facture.consultation.patient')->findOrFail($id);
        Gate::authorize('view', $paiement);
        return new PaiementResource($paiement);
    }
}
