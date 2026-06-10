<?php

namespace App\Services;

use App\Models\Facture;
use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfService
{
    /**
     * Generate an HTTP response with the invoice PDF.
     */
    public function generateFacture(Facture $facture): Response
    {
        $pdf = Pdf::loadView('pdf.facture', compact('facture'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("facture-{$facture->numero_facture}.pdf");
    }

    /**
     * Generate an HTTP response with a payment receipt PDF.
     */
    public function generateRecu(Paiement $paiement): Response
    {
        $pdf = Pdf::loadView('pdf.recu', compact('paiement'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("recu-{$paiement->id}.pdf");
    }
}
