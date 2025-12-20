<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSortieRequest extends FormRequest
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
            'details' => 'required|array|min:1',
            'details.*.article_id' => 'required|exists:articles,id',
            'details.*.depot_id' => 'required|exists:depots,id',
            'details.*.quantite' => 'required|numeric|min:0.01',
            'details.*.prix_unitaire' => 'required|numeric|min:0',
            'details.*.observations' => 'nullable|string',
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
            'details.required' => 'Vous devez ajouter au moins un article.',
            'details.*.article_id.exists' => 'Un ou plusieurs articles sélectionnés n\'existent pas.',
            'details.*.depot_id.exists' => 'Un ou plusieurs dépôts sélectionnés n\'existent pas.',
            'details.*.quantite.min' => 'La quantité doit être supérieure à 0.',
            'details.*.prix_unitaire.min' => 'Le prix unitaire ne peut pas être négatif.',
        ];
    }
}
