<?php

namespace App\Http\Controllers;

use App\Models\ClinicSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClinicSettingController extends Controller
{
    /**
     * Show the clinic settings form (admin only).
     */
    public function edit(): View
    {
        $settings = ClinicSetting::getInstance();
        Gate::authorize('view', $settings);

        return view('settings.edit', compact('settings'));
    }

    /**
     * Persist updated clinic settings (admin only).
     */
    public function update(Request $request): RedirectResponse
    {
        $settings = ClinicSetting::getInstance();
        Gate::authorize('update', $settings);

        $validated = $request->validate([
            'nom_clinique' => ['required', 'string', 'max:150'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:100'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'site_web' => ['nullable', 'url', 'max:150'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('clinic', 'public');
        }

        unset($validated['logo']);
        $settings->update($validated);

        return back()->with('success', 'Paramètres de la clinique mis à jour.');
    }
}
