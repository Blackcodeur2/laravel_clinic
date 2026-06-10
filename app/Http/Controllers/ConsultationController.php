<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultationRequest;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use App\Repositories\ConsultationRepositoryInterface;
use App\Services\FactureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function __construct(
        protected ConsultationRepositoryInterface $consultationRepository,
        protected FactureService $factureService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Consultation::class);

        $filters = $request->only(['search', 'medecin_id', 'date_consultation']);
        $consultations = $this->consultationRepository->all($filters);

        $medecins = User::whereHas('role', function ($query) {
            $query->where('nom', 'RESPONSABLE');
        })->orderBy('nom')->get();

        return view('consultations.index', compact('consultations', 'medecins'));
    }

    public function create(): View
    {
        Gate::authorize('create', Consultation::class);

        $patients = Patient::orderBy('nom')->get();
        // Fetch users who are doctors (RESPONSABLE)
        $medecins = User::whereHas('role', function ($query) {
            $query->where('nom', 'RESPONSABLE');
        })->orderBy('nom')->get();

        return view('consultations.create', compact('patients', 'medecins'));
    }

    public function store(ConsultationRequest $request): RedirectResponse
    {
        Gate::authorize('create', Consultation::class);

        $consultation = $this->consultationRepository->create($request->validated());

        // Auto create the invoice
        $this->factureService->createFactureForConsultation($consultation);

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation enregistrée avec succès. Facture générée automatiquement.');
    }

    public function show(Consultation $consultation): View
    {
        Gate::authorize('view', $consultation);
        $consultation->load(['patient', 'medecin', 'facture.ligneFactures.serviceMedical', 'facture.ligneFactures.medicament', 'facture.paiements']);

        return view('consultations.show', compact('consultation'));
    }

    public function edit(Consultation $consultation): View
    {
        Gate::authorize('update', $consultation);

        $patients = Patient::orderBy('nom')->get();
        $medecins = User::whereHas('role', function ($query) {
            $query->where('nom', 'RESPONSABLE');
        })->orderBy('nom')->get();

        return view('consultations.edit', compact('consultation', 'patients', 'medecins'));
    }

    public function update(ConsultationRequest $request, Consultation $consultation): RedirectResponse
    {
        Gate::authorize('update', $consultation);
        $this->consultationRepository->update($consultation->id, $request->validated());

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation mise à jour avec succès.');
    }

    public function destroy(Consultation $consultation): RedirectResponse
    {
        Gate::authorize('delete', $consultation);
        $this->consultationRepository->delete($consultation->id);

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation supprimée avec succès.');
    }
}
