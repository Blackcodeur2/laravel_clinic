<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicamentRequest;
use App\Models\Medicament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MedicamentController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Medicament::class);

        $search = $request->input('search');

        $medicaments = Medicament::when($search, fn ($q) => $q->where('nom', 'like', "%{$search}%")
            ->orWhere('reference', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('medicaments.index', compact('medicaments', 'search'));
    }

    public function create(): View
    {
        Gate::authorize('create', Medicament::class);

        return view('medicaments.create');
    }

    public function store(MedicamentRequest $request): RedirectResponse
    {
        Gate::authorize('create', Medicament::class);
        Medicament::create($request->validated());

        return redirect()->route('medicaments.index')
            ->with('success', 'Médicament ajouté avec succès.');
    }

    public function edit(Medicament $medicament): View
    {
        Gate::authorize('update', $medicament);

        return view('medicaments.edit', compact('medicament'));
    }

    public function update(MedicamentRequest $request, Medicament $medicament): RedirectResponse
    {
        Gate::authorize('update', $medicament);
        $medicament->update($request->validated());

        return redirect()->route('medicaments.index')
            ->with('success', 'Médicament mis à jour avec succès.');
    }

    public function destroy(Medicament $medicament): RedirectResponse
    {
        Gate::authorize('delete', $medicament);
        $medicament->delete();

        return redirect()->route('medicaments.index')
            ->with('success', 'Médicament supprimé avec succès.');
    }
}
