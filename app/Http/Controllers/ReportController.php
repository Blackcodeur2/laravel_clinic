<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\LigneFacture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display the monthly operations and financial report.
     */
    public function monthlyReport(Request $request): View
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isResponsable()) {
            abort(403, 'Accès non autorisé.');
        }

        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        // 1. KPIs
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

        // 2. Doctor performance
        $consultationsPerDoctor = Consultation::whereMonth('date_consultation', $month)
            ->whereYear('date_consultation', $year)
            ->with('medecin')
            ->selectRaw('medecin_id, count(*) as count')
            ->groupBy('medecin_id')
            ->orderByDesc('count')
            ->get();

        // 3. Payment methods Breakdown
        $paymentsByMethod = Paiement::whereMonth('date_paiement', $month)
            ->whereYear('date_paiement', $year)
            ->selectRaw('mode_paiement, sum(montant) as total')
            ->groupBy('mode_paiement')
            ->orderByDesc('total')
            ->get();

        // 4. Top 5 services requested
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

        // 5. Top 5 medications dispensed
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

        return view('reports.show', compact(
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
        ));
    }
}
