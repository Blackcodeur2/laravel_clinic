<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\Facture;
use App\Models\LigneFacture;
use App\Models\Paiement;
use App\Models\Medicament;
use App\Models\ServiceMedical;
use Illuminate\Support\Facades\DB;
use Exception;

class FactureService
{
    /**
     * Create a basic invoice for a consultation.
     */
    public function createFactureForConsultation(Consultation $consultation): Facture
    {
        return DB::transaction(function () use ($consultation) {
            // Generate a unique invoice number
            $year = date('Y');
            $random = strtoupper(bin2hex(random_bytes(3)));
            $numero = "FAC-{$year}-{$random}";

            return Facture::create([
                'consultation_id' => $consultation->id,
                'numero_facture' => $numero,
                'date_facture' => now(),
                'montant_total' => 0.00,
                'montant_paye' => 0.00,
                'reste_a_payer' => 0.00,
                'statut' => 'IMPAYEE',
            ]);
        });
    }

    /**
     * Update an invoice's total amounts and payment status.
     */
    public function updateFactureTotals(Facture $facture): void
    {
        $total = (float) $facture->ligneFactures()->sum('total');
        $paye = (float) $facture->paiements()->sum('montant');
        $reste = max(0.0, $total - $paye);

        $statut = 'IMPAYEE';
        if ($paye > 0) {
            if ($paye >= $total) {
                $statut = 'PAYEE';
            } else {
                $statut = 'PARTIELLEMENT_PAYEE';
            }
        }

        $facture->update([
            'montant_total' => $total,
            'montant_paye' => $paye,
            'reste_a_payer' => $reste,
            'statut' => $statut,
        ]);
    }

    /**
     * Add a line item to an invoice.
     * Handles stock validation and automatic stock decrementing for medications.
     */
    public function addLigne(Facture $facture, array $itemData): LigneFacture
    {
        return DB::transaction(function () use ($facture, $itemData) {
            $serviceId = $itemData['service_medical_id'] ?? null;
            $medicamentId = $itemData['medicament_id'] ?? null;
            $quantite = (int) ($itemData['quantite'] ?? 1);
            $prixUnitaire = (float) ($itemData['prix_unitaire'] ?? 0);

            if ($medicamentId) {
                $medicament = Medicament::lockForUpdate()->findOrFail($medicamentId);
                if ($medicament->stock < $quantite) {
                    throw new Exception("Stock insuffisant pour le médicament: {$medicament->nom} (Stock actuel: {$medicament->stock})");
                }
                // Decrement stock automatically
                $medicament->decrement('stock', $quantite);
            }

            $total = $quantite * $prixUnitaire;

            $ligne = LigneFacture::create([
                'facture_id' => $facture->id,
                'service_medical_id' => $serviceId,
                'medicament_id' => $medicamentId,
                'quantite' => $quantite,
                'prix_unitaire' => $prixUnitaire,
                'total' => $total,
            ]);

            $this->updateFactureTotals($facture);

            return $ligne;
        });
    }

    /**
     * Remove a line item from an invoice.
     * Restores medication stock automatically.
     */
    public function removeLigne(LigneFacture $ligne): void
    {
        DB::transaction(function () use ($ligne) {
            $facture = $ligne->facture;

            if ($ligne->medicament_id) {
                $medicament = Medicament::findOrFail($ligne->medicament_id);
                // Restore stock
                $medicament->increment('stock', $ligne->quantite);
            }

            $ligne->delete();

            $this->updateFactureTotals($facture);
        });
    }

    /**
     * Add a payment record to an invoice.
     */
    public function addPaiement(Facture $facture, array $paiementData): Paiement
    {
        return DB::transaction(function () use ($facture, $paiementData) {
            $montant = (float) $paiementData['montant'];

            if ($montant <= 0) {
                throw new Exception("Le montant du paiement doit être supérieur à zéro.");
            }

            $paiement = Paiement::create([
                'facture_id' => $facture->id,
                'montant' => $montant,
                'mode_paiement' => $paiementData['mode_paiement'],
                'reference' => $paiementData['reference'] ?? null,
                'date_paiement' => $paiementData['date_paiement'] ?? now(),
            ]);

            $this->updateFactureTotals($facture);

            return $paiement;
        });
    }
}
