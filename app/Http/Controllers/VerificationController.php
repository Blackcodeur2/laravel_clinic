<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Paiement;
use Illuminate\View\View;

class VerificationController extends Controller
{
    /**
     * Verify an invoice by its token.
     */
    public function facture(string $token): View
    {
        $facture = Facture::with([
            'consultation.patient',
            'consultation.medecin',
            'ligneFactures.serviceMedical',
            'ligneFactures.medicament',
            'paiements',
        ])->where('verification_token', $token)->firstOrFail();

        return view('verification.facture', compact('facture'));
    }

    /**
     * Verify a payment receipt by its token.
     */
    public function recu(string $token): View
    {
        $paiement = Paiement::with([
            'facture.consultation.patient',
            'facture.consultation.medecin',
        ])->where('verification_token', $token)->firstOrFail();

        return view('verification.recu', compact('paiement'));
    }
}
