<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Facture;
use App\Models\Medicament;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     */
    public function index(): RedirectResponse|View
    {
        if (auth()->user()->isCaissier()) {
            return redirect()->route('factures.index');
        }

        $totalPatients = Patient::count();
        
        $consultationsToday = Consultation::whereDate('date_consultation', now()->toDateString())->count();
        
        $unpaidInvoicesCount = Facture::whereIn('statut', ['IMPAYEE', 'PARTIELLEMENT_PAYEE'])->count();
        
        $monthlyRevenue = Paiement::whereMonth('date_paiement', now()->month)
            ->whereYear('date_paiement', now()->year)
            ->sum('montant');

        $latestPatients = Patient::latest()->take(5)->get();
        $latestInvoices = Facture::with('consultation.patient')->latest()->take(5)->get();
        $recentConsultations = Consultation::with('patient', 'medecin')->latest()->take(5)->get();

        // 1. Revenue Evolution (last 6 months)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $monthlyRevenueData = Paiement::selectRaw("DATE_FORMAT(date_paiement, '%Y-%m') as month, SUM(montant) as total")
            ->where('date_paiement', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyUnpaidData = Facture::selectRaw("DATE_FORMAT(date_facture, '%Y-%m') as month, SUM(reste_a_payer) as total")
            ->where('date_facture', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $revenueMonths = [];
        $revenueTotals = [];
        $unpaidTotals = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = ucfirst($date->translatedFormat('M Y'));
            $revenueMonths[] = $monthLabel;

            $matchedRev = $monthlyRevenueData->firstWhere('month', $monthKey);
            $revenueTotals[] = $matchedRev ? (float)$matchedRev->total : 0.0;

            $matchedUnpaid = $monthlyUnpaidData->firstWhere('month', $monthKey);
            $unpaidTotals[] = $matchedUnpaid ? (float)$matchedUnpaid->total : 0.0;
        }

        // 2. Payments breakdown by method
        $paymentsByMethod = Paiement::selectRaw('mode_paiement, SUM(montant) as total')
            ->groupBy('mode_paiement')
            ->orderByDesc('total')
            ->get();

        $paymentLabels = [];
        $paymentTotals = [];
        foreach ($paymentsByMethod as $payment) {
            $paymentLabels[] = ucfirst(strtolower(str_replace('_', ' ', $payment->mode_paiement)));
            $paymentTotals[] = (float)$payment->total;
        }

        // 3. Consultations per doctor (current month)
        $doctorsActivity = Consultation::selectRaw('medecin_id, COUNT(*) as count')
            ->whereMonth('date_consultation', now()->month)
            ->whereYear('date_consultation', now()->year)
            ->with('medecin')
            ->groupBy('medecin_id')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        $doctorLabels = [];
        $doctorCounts = [];
        foreach ($doctorsActivity as $act) {
            $name = $act->medecin ? "Dr. " . $act->medecin->prenom . " " . $act->medecin->nom : 'Non assigné';
            $doctorLabels[] = $name;
            $doctorCounts[] = (int)$act->count;
        }

        // 4. Medication alerts
        $today = now()->toDateString();
        $alertExpired = Medicament::whereNotNull('date_peremption')
            ->whereDate('date_peremption', '<', $today)
            ->orderBy('date_peremption')
            ->get();

        $alertNearExpiration = Medicament::whereNotNull('date_peremption')
            ->whereDate('date_peremption', '>=', $today)
            ->whereDate('date_peremption', '<=', now()->addDays(30)->toDateString())
            ->orderBy('date_peremption')
            ->get();

        $alertLowStock = Medicament::whereColumn('stock', '<=', 'stock_alerte')
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->get();

        $alertOutOfStock = Medicament::where('stock', 0)->orderBy('nom')->get();

        return view('dashboard', compact(
            'totalPatients',
            'consultationsToday',
            'unpaidInvoicesCount',
            'monthlyRevenue',
            'latestPatients',
            'latestInvoices',
            'recentConsultations',
            'revenueMonths',
            'revenueTotals',
            'unpaidTotals',
            'paymentLabels',
            'paymentTotals',
            'doctorLabels',
            'doctorCounts',
            'alertExpired',
            'alertNearExpiration',
            'alertLowStock',
            'alertOutOfStock'
        ));
    }
}
