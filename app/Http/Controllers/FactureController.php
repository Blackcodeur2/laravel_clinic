<?php

namespace App\Http\Controllers;

use App\Http\Requests\FactureRequest;
use App\Models\Consultation;
use App\Models\Facture;
use App\Models\LigneFacture;
use App\Models\Medicament;
use App\Models\ServiceMedical;
use App\Repositories\FactureRepositoryInterface;
use App\Services\FactureService;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FactureController extends Controller
{
    public function __construct(
        protected FactureRepositoryInterface $factureRepository,
        protected FactureService $factureService,
        protected PdfService $pdfService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Facture::class);

        $query = Facture::with('consultation.patient')->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('numero_facture', 'like', "%{$search}%")
                    ->orWhereHas('consultation.patient', function ($pq) use ($search) {
                        $pq->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%");
                    });
            });
        }

        $factures = $query->paginate(15)->withQueryString();

        return view('factures.index', compact('factures'));
    }

    public function create(): View
    {
        Gate::authorize('create', Facture::class);

        $consultations = Consultation::with('patient')
            ->doesntHave('facture')
            ->orderByDesc('date_consultation')
            ->get();

        return view('factures.create', compact('consultations'));
    }

    public function store(FactureRequest $request): RedirectResponse
    {
        Gate::authorize('create', Facture::class);

        $consultation = Consultation::findOrFail($request->consultation_id);
        $facture = $this->factureService->createFactureForConsultation($consultation);

        return redirect()->route('factures.show', $facture)
            ->with('success', 'Facture créée avec succès.');
    }

    public function show(Facture $facture): View
    {
        Gate::authorize('view', $facture);

        $facture->load([
            'consultation.patient',
            'consultation.medecin',
            'ligneFactures.serviceMedical',
            'ligneFactures.medicament',
            'paiements',
        ]);

        $services = ServiceMedical::orderBy('nom')->get();
        $medicaments = Medicament::where('stock', '>', 0)->orderBy('nom')->get();

        return view('factures.show', compact('facture', 'services', 'medicaments'));
    }

    public function destroy(Facture $facture): RedirectResponse
    {
        Gate::authorize('delete', $facture);
        $facture->delete();

        return redirect()->route('factures.index')
            ->with('success', 'Facture supprimée avec succès.');
    }

    /**
     * Add a line item to the invoice.
     */
    public function addLigne(Request $request, Facture $facture): RedirectResponse
    {
        Gate::authorize('update', $facture);

        $validated = $request->validate([
            'type' => ['required', 'in:service,medicament'],
            'service_medical_id' => ['nullable', 'exists:service_medicals,id'],
            'medicament_id' => ['nullable', 'exists:medicaments,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $this->factureService->addLigne($facture, $validated);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Ligne ajoutée avec succès.');
    }

    /**
     * Remove a line item from the invoice.
     */
    public function removeLigne(Facture $facture, LigneFacture $ligne): RedirectResponse
    {
        Gate::authorize('update', $facture);

        try {
            $this->factureService->removeLigne($ligne);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Ligne supprimée.');
    }

    /**
     * Generate PDF for an invoice.
     */
    public function pdf(Facture $facture): Response
    {
        Gate::authorize('view', $facture);

        $facture->load([
            'consultation.patient',
            'consultation.medecin',
            'ligneFactures.serviceMedical',
            'ligneFactures.medicament',
            'paiements',
        ]);

        return $this->pdfService->generateFacture($facture);
    }
}
