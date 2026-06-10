<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Paiement;
use App\Services\FactureService;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class PaiementController extends Controller
{
    public function __construct(
        protected FactureService $factureService,
        protected PdfService $pdfService
    ) {}

    /**
     * Store a new payment for an invoice.
     */
    public function store(Request $request, Facture $facture): RedirectResponse
    {
        Gate::authorize('update', $facture);

        $validated = $request->validate([
            'montant' => ['required', 'numeric', 'min:0.01'],
            'mode_paiement' => ['required', 'in:ESPECES,CHEQUE,VIREMENT,MOBILE'],
            'reference' => ['nullable', 'string', 'max:100'],
            'date_paiement' => ['required', 'date'],
        ]);

        try {
            $this->factureService->addPaiement($facture, $validated);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Delete a payment and recompute invoice totals.
     */
    public function destroy(Paiement $paiement): RedirectResponse
    {
        $facture = $paiement->facture;
        Gate::authorize('update', $facture);

        $paiement->delete();
        $this->factureService->updateFactureTotals($facture);

        return back()->with('success', 'Paiement supprimé.');
    }

    /**
     * Generate a payment receipt PDF.
     */
    public function recu(Paiement $paiement): Response
    {
        $paiement->load(['facture.consultation.patient']);
        Gate::authorize('view', $paiement->facture);

        return $this->pdfService->generateRecu($paiement);
    }
}
