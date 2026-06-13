<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Facture;
use App\Models\LigneFacture;
use App\Models\Paiement;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(protected PdfService $pdfService) {}

    /**
     * Build the report data array for a given month/year.
     */
    private function buildReportData(string $month, string $year): array
    {
        $totalConsultations = Consultation::whereMonth('date_consultation', $month)
            ->whereYear('date_consultation', $year)
            ->count();

        $totalInvoiced = Facture::whereMonth('date_facture', $month)
            ->whereYear('date_facture', $year)
            ->sum('montant_total');

        $totalCollected = Paiement::whereMonth('date_paiement', $month)
            ->whereYear('date_paiement', $year)
            ->sum('montant');

        $totalUnpaid = Facture::whereMonth('date_facture', $month)
            ->whereYear('date_facture', $year)
            ->sum('reste_a_payer');

        $consultationsPerDoctor = Consultation::whereMonth('date_consultation', $month)
            ->whereYear('date_consultation', $year)
            ->with('medecin')
            ->selectRaw('medecin_id, count(*) as count')
            ->groupBy('medecin_id')
            ->orderByDesc('count')
            ->get();

        $paymentsByMethod = Paiement::whereMonth('date_paiement', $month)
            ->whereYear('date_paiement', $year)
            ->selectRaw('mode_paiement, sum(montant) as total')
            ->groupBy('mode_paiement')
            ->orderByDesc('total')
            ->get();

        $topServices = LigneFacture::whereHas('facture', function ($q) use ($month, $year) {
            $q->whereMonth('date_facture', $month)
                ->whereYear('date_facture', $year);
        })
            ->whereNotNull('service_medical_id')
            ->with('serviceMedical')
            ->selectRaw('service_medical_id, sum(quantite) as count, sum(total) as revenue')
            ->groupBy('service_medical_id')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        $topMedicaments = LigneFacture::whereHas('facture', function ($q) use ($month, $year) {
            $q->whereMonth('date_facture', $month)
                ->whereYear('date_facture', $year);
        })
            ->whereNotNull('medicament_id')
            ->with('medicament')
            ->selectRaw('medicament_id, sum(quantite) as count, sum(total) as revenue')
            ->groupBy('medicament_id')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        return compact(
            'month',
            'year',
            'totalConsultations',
            'totalInvoiced',
            'totalCollected',
            'totalUnpaid',
            'consultationsPerDoctor',
            'paymentsByMethod',
            'topServices',
            'topMedicaments'
        );
    }

    /**
     * Display the monthly operations and financial report.
     */
    public function monthlyReport(Request $request): View
    {
        if (! auth()->user()->isAdmin() && ! auth()->user()->isResponsable()) {
            abort(403, 'Accès non autorisé.');
        }

        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        return view('reports.show', $this->buildReportData($month, $year));
    }

    /**
     * Download the monthly report as a PDF file.
     */
    public function pdf(Request $request): Response
    {
        if (! auth()->user()->isAdmin() && ! auth()->user()->isResponsable()) {
            abort(403, 'Accès non autorisé.');
        }

        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        return $this->pdfService->generateReport($this->buildReportData($month, $year), $month, $year);
    }
}
