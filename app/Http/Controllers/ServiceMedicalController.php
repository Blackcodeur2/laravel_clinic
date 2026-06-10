<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceMedicalRequest;
use App\Models\ServiceMedical;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ServiceMedicalController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', ServiceMedical::class);
        $services = ServiceMedical::latest()->paginate(20);

        return view('services.index', compact('services'));
    }

    public function create(): View
    {
        Gate::authorize('create', ServiceMedical::class);

        return view('services.create');
    }

    public function store(ServiceMedicalRequest $request): RedirectResponse
    {
        Gate::authorize('create', ServiceMedical::class);
        ServiceMedical::create($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Service médical ajouté avec succès.');
    }

    public function edit(ServiceMedical $service): View
    {
        Gate::authorize('update', $service);

        return view('services.edit', compact('service'));
    }

    public function update(ServiceMedicalRequest $request, ServiceMedical $service): RedirectResponse
    {
        Gate::authorize('update', $service);
        $service->update($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Service médical mis à jour avec succès.');
    }

    public function destroy(ServiceMedical $service): RedirectResponse
    {
        Gate::authorize('delete', $service);
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service médical supprimé avec succès.');
    }
}
