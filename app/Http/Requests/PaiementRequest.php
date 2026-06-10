<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaiementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'facture_id' => ['required', 'exists:factures,id'],
            'montant' => ['required', 'numeric', 'min:0.01'],
            'mode_paiement' => ['required', 'in:ESPECES,OM,MOMO,CARTE_BANCAIRE,VIREMENT'],
            'reference' => ['nullable', 'string', 'max:255'],
            'date_paiement' => ['required', 'date'],
        ];
    }
}
