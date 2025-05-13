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
            ],            'activite_principale' => ['required', 'string', 'min:3', 'max:255'],            'ice' => [
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
            'activite_principale.required' => 'L\'activité principale est requise',            'activite_principale.min' => 'L\'activité principale doit contenir au moins 3 caractères',
            'ice.required' => 'Le numéro ICE est requis',
            'ice.numeric' => 'Le numéro ICE doit contenir uniquement des chiffres',
            'ice.regex' => 'Le numéro ICE doit avoir un format valide',
            'ice.unique' => 'Ce numéro ICE est déjà utilisé',
            'email.email' => 'L\'adresse email doit être valide',
            'email.max' => 'L\'adresse email ne doit pas dépasser 255 caractères',
            'telephone.max' => 'Le numéro de téléphone ne doit pas dépasser 20 caractères',
        ];
    }
}
