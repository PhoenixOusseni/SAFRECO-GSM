<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSortieRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'nullable|exists:clients,id',
            'date_sortie' => 'required|date',
            'numero_facture' => 'nullable|string|max:255',
            'type_sortie' => 'required|in:vente,transfert,destruction,inventaire',
            'observations' => 'nullable|string',
            'statut' => 'nullable|in:validee,en_attente,rejetee',
            'articles' => 'required|array|min:1',
            'articles.*' => 'required|exists:articles,id',
            'depots' => 'required|array|min:1',
            'depots.*' => 'required|exists:depots,id',
            'quantites' => 'required|array|min:1',
            'quantites.*' => 'required|numeric|min:0.01',
            'prix_unitaires' => 'required|array|min:1',
            'prix_unitaires.*' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'date_sortie.required' => 'La date de sortie est obligatoire.',
            'date_sortie.date' => 'La date de sortie doit être une date valide.',
            'type_sortie.required' => 'Le type de sortie est obligatoire.',
            'type_sortie.in' => 'Le type de sortie doit être: vente, transfert, destruction ou inventaire.',
            'statut.in' => 'Le statut doit être: validee, en_attente ou rejetee.',
            'articles.required' => 'Vous devez sélectionner au moins un article.',
            'articles.*.exists' => 'Un ou plusieurs articles sélectionnés n\'existent pas.',
            'depots.required' => 'Vous devez sélectionner au moins un dépôt.',
            'depots.*.exists' => 'Un ou plusieurs dépôts sélectionnés n\'existent pas.',
            'quantites.*.min' => 'La quantité doit être supérieure à 0.',
            'prix_unitaires.*.min' => 'Le prix unitaire ne peut pas être négatif.',
        ];
    }
}
