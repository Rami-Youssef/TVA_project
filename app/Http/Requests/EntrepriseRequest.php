<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EntrepriseRequest extends FormRequest
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
            'nom' => ['required', 'string', 'min:3', 'max:255'],
            'siege_social' => ['required', 'string', 'min:3', 'max:255'],
            'form_juridique' => [
                'required', 
                'string', 
                Rule::in([
                    'Société par Actions Simplifiée (SAS)',
                    'Société à Responsabilité Limitée (SARL)',
                    'Société Anonyme (SA)',
                    'Entreprise Individuelle (EI)',
                    'Auto-Entrepreneur'
                ])
            ],
            'activite_principale' => ['required', 'string', 'min:3', 'max:255'],
            'numero_societe' => [
                'required',
                'numeric',
                'regex:/^[0-9]{9}([0-9]{5})?$/', // Either 9 (SIREN) or 14 (SIRET) digits
                Rule::unique('entreprises')->ignore($this->route('entreprise')),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la société est requis',
            'nom.min' => 'Le nom doit contenir au moins 3 caractères',
            'siege_social.required' => 'L\'adresse du siège social est requise',
            'siege_social.min' => 'L\'adresse doit contenir au moins 3 caractères',
            'form_juridique.required' => 'La forme juridique est requise',
            'form_juridique.in' => 'La forme juridique sélectionnée n\'est pas valide',
            'activite_principale.required' => 'L\'activité principale est requise',
            'activite_principale.min' => 'L\'activité principale doit contenir au moins 3 caractères',
            'numero_societe.required' => 'Le numéro de société est requis',
            'numero_societe.numeric' => 'Le numéro de société doit contenir uniquement des chiffres',
            'numero_societe.regex' => 'Le numéro de société doit être un SIREN (9 chiffres) ou un SIRET (14 chiffres)',
            'numero_societe.unique' => 'Ce numéro de société est déjà utilisé',
        ];
    }
}
