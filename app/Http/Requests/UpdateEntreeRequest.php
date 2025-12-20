<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntreeRequest extends FormRequest
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
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'date_entree' => 'required|date',
            'numero_facture' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'statut' => 'nullable|in:recu,en_attente,rejete',
            'articles' => 'required|array|min:1',
            'articles.*' => 'required|exists:articles,id',
            'depots' => 'required|array|min:1',
            'depots.*' => 'required|exists:depots,id',
            'stock' => 'required|array|min:1',
            'stock.*' => 'required|numeric|min:0.01',
            'prix_achat' => 'required|array|min:1',
            'prix_achat.*' => 'required|numeric|min:0',
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
            'fournisseur_id.exists' => 'Le fournisseur sélectionné n\'existe pas.',
            'date_entree.required' => 'La date d\'entrée est obligatoire.',
            'date_entree.date' => 'La date d\'entrée doit être une date valide.',
            'statut.in' => 'Le statut doit être: recu, en_attente ou rejete.',
            'articles.required' => 'Vous devez sélectionner au moins un article.',
            'articles.*.exists' => 'Un ou plusieurs articles sélectionnés n\'existent pas.',
            'depots.required' => 'Vous devez sélectionner au moins un dépôt.',
            'depots.*.exists' => 'Un ou plusieurs dépôts sélectionnés n\'existent pas.',
            'stock.*.min' => 'La quantité doit être supérieure à 0.',
            'prix_achat.*.min' => 'Le prix d\'achat ne peut pas être négatif.',
        ];
    }
}
