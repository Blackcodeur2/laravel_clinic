<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceMedicalRequest extends FormRequest
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
        $serviceId = $this->route('service') ?: ($this->route('services') ?: null);
        if (is_object($serviceId)) {
            $serviceId = $serviceId->id;
        }

        return [
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('service_medicals', 'code')->ignore($serviceId),
            ],
            'nom' => ['required', 'string', 'max:255'],
            'prix' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }
}
