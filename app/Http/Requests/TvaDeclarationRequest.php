<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class TvaDeclarationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'entreprise_id' => ['required', 'exists:entreprises,id'],
            'type' => ['required', Rule::in(['mensuelle', 'trimestrielle', 'annuelle'])],
            'periode' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $type = $this->input('type');
                    $pattern = match($type) {
                        'mensuelle' => '/^\d{4}-(?:0[1-9]|1[0-2])$/', // Format: YYYY-MM
                        'trimestrielle' => '/^\d{4}-Q[1-4]$/',       // Format: YYYY-Q#
                        'annuelle' => '/^\d{4}$/',                   // Format: YYYY
                        default => null
                    };

                    if ($pattern && !preg_match($pattern, $value)) {
                        $format = match($type) {
                            'mensuelle' => 'AAAA-MM (ex: 2025-01)',
                            'trimestrielle' => 'AAAA-Q# (ex: 2025-Q1)',
                            'annuelle' => 'AAAA (ex: 2025)',
                            default => ''
                        };
                        $fail(__('Le format de la période doit être ' . $format));
                    }
                }
            ],
            'montant' => ['required', 'numeric', 'min:0'],
            'date_declaration' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'entreprise_id.required' => 'L\'entreprise est requise',
            'entreprise_id.exists' => 'L\'entreprise sélectionnée n\'existe pas',
            'type.required' => 'Le type de TVA est requis',
            'type.in' => 'Le type de TVA doit être mensuelle, trimestrielle ou annuelle',
            'periode.required' => 'La période est requise',
            'montant.required' => 'Le montant est requis',
            'montant.numeric' => 'Le montant doit être un nombre',
            'montant.min' => 'Le montant ne peut pas être négatif',
            'date_declaration.required' => 'La date de déclaration est requise',
            'date_declaration.date' => 'La date de déclaration doit être une date valide',
            'date_declaration.before_or_equal' => 'La date de déclaration ne peut pas être dans le futur',
        ];
    }
}