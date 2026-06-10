<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FactureRequest extends FormRequest
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
        // Invoice is created for a consultation, then items are added dynamically
        return [
            'consultation_id' => ['sometimes', 'required', 'exists:consultations,id'],
            'lines' => ['nullable', 'array'],
            'lines.*.service_medical_id' => ['nullable', 'exists:service_medicals,id'],
            'lines.*.medicament_id' => ['nullable', 'exists:medicaments,id'],
            'lines.*.quantite' => ['required_with:lines', 'integer', 'min:1'],
            'lines.*.prix_unitaire' => ['required_with:lines', 'numeric', 'min:0'],
        ];
    }
}
