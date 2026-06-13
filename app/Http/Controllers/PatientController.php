<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Models\Patient;
use App\Repositories\PatientRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function __construct(
        protected PatientRepositoryInterface $patientRepository
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Patient::class);

        $filters = $request->only(['search', 'sexe']);
        $patients = $this->patientRepository->all($filters);
        $search = $request->input('search');

        return view('patients.index', compact('patients', 'search'));
    }

    public function create(): View
    {
        Gate::authorize('create', Patient::class);

        return view('patients.create');
    }

    public function store(PatientRequest $request): RedirectResponse
    {
        Gate::authorize('create', Patient::class);
        $this->patientRepository->create($request->validated());

        return redirect()->route('patients.index')
            ->with('success', 'Patient enregistré avec succès.');
    }

    public function edit(Patient $patient): View
    {
        Gate::authorize('update', $patient);

        return view('patients.edit', compact('patient'));
    }

    public function update(PatientRequest $request, Patient $patient): RedirectResponse
    {
        Gate::authorize('update', $patient);
        $this->patientRepository->update($patient->id, $request->validated());

        return redirect()->route('patients.index')
            ->with('success', 'Patient mis à jour avec succès.');
    }

    public function show(Patient $patient): View
    {
        Gate::authorize('view', $patient);

        $consultations = $patient->consultations()
            ->with(['medecin', 'facture.paiements'])
            ->latest()
            ->get();

        $factures = \App\Models\Facture::whereHas('consultation', function ($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })
        ->with(['ligneFactures.serviceMedical', 'ligneFactures.medicament', 'paiements'])
        ->latest()
        ->get();

        $paiements = \App\Models\Paiement::whereHas('facture.consultation', function ($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })
        ->with('facture')
        ->latest()
        ->get();

        return view('patients.show', compact('patient', 'consultations', 'factures', 'paiements'));
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        Gate::authorize('delete', $patient);
        $this->patientRepository->delete($patient->id);

        return redirect()->route('patients.index')
            ->with('success', 'Patient supprimé avec succès.');
    }
}
