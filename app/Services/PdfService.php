<?php

namespace App\Services;

use App\Models\Facture;
use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfService
{
    /**
     * Generate an HTTP response with the invoice PDF.
     */
    public function generateFacture(Facture $facture): Response
    {
        $qrCodeUrl = route('verify.facture', $facture->verification_token);
        $qrCode = base64_encode(
            QrCode::format('svg')->size(120)->margin(1)->generate($qrCodeUrl)
        );

        $pdf = Pdf::loadView('pdf.facture', compact('facture', 'qrCode', 'qrCodeUrl'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("facture-{$facture->numero_facture}.pdf");
    }

    /**
     * Generate an HTTP response with a payment receipt PDF.
     */
    public function generateRecu(Paiement $paiement): Response
    {
        $qrCodeUrl = route('verify.recu', $paiement->verification_token);
        $qrCode = base64_encode(
            QrCode::format('svg')->size(120)->margin(1)->generate($qrCodeUrl)
        );

        $pdf = Pdf::loadView('pdf.recu', compact('paiement', 'qrCode', 'qrCodeUrl'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("recu-{$paiement->id}.pdf");
    }

    /**
     * Generate an HTTP response with a monthly activity report PDF.
     */
    public function generateReport(array $data, string $month, string $year): Response
    {
        $pdf = Pdf::loadView('pdf.rapport', $data)
            ->setPaper('a4', 'portrait');

        $monthName = \Carbon\Carbon::create(null, (int) $month, 1)->format('Y-m');

        return $pdf->download("rapport-activite-{$monthName}.pdf");
    }
}
