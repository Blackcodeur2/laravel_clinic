<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Facture;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     */
    public function index(): View
    {
        $totalPatients = Patient::count();
        
        $consultationsToday = Consultation::whereDate('date_consultation', now()->toDateString())->count();
        
        $unpaidInvoicesCount = Facture::whereIn('statut', ['IMPAYEE', 'PARTIELLEMENT_PAYEE'])->count();
        
        $monthlyRevenue = Paiement::whereMonth('date_paiement', now()->month)
            ->whereYear('date_paiement', now()->year)
            ->sum('montant');

        $latestPatients = Patient::latest()->take(5)->get();
        $latestInvoices = Facture::with('consultation.patient')->latest()->take(5)->get();
        $recentConsultations = Consultation::with('patient', 'medecin')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalPatients',
            'consultationsToday',
            'unpaidInvoicesCount',
            'monthlyRevenue',
            'latestPatients',
            'latestInvoices',
            'recentConsultations'
        ));
    }
}
