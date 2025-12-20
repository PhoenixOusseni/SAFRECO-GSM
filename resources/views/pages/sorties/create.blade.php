@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Sorties de Stock</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Gestion des Sorties</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_sorties.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>&nbsp; Retour à la liste des Sorties
            </a>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Enregistrer une Sortie de Stock</h5>
                        <form action="{{ route('gestions_sorties.store') }}" method="POST">
                            @csrf
                            <!-- En-tête Sortie -->
                            <div class="mb-4" style="background: rgb(232, 240, 243); padding: 15px; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-box-seam"></i> Informations de la Sortie</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="numero_sortie" class="small">Numéro Sortie</label>
                                        <input type="text" class="form-control" id="numero_sortie" name="numero_sortie"
                                            placeholder="Auto-généré" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="date_sortie" class="small">Date Sortie <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date_sortie" name="date_sortie"
                                            required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="type_sortie" class="small">Type de Sortie <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="type_sortie" name="type_sortie" required>
                                            <option value="">-- Sélectionner un type --</option>
                                            <option value="vente">Vente</option>
                                            <option value="transfert">Transfert</option>
                                            <option value="destruction">Destruction</option>
                                            <option value="inventaire">Inventaire</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="client_id" class="small">Client</label>
                                        <select class="form-select" id="client_id" name="client_id">
                                            <option value="">-- Sélectionner un client --</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">
                                                    {{ $client->nom ?? $client->raison_sociale }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="numero_facture" class="small">Numéro Facture</label>
                                        <input type="text" class="form-control" id="numero_facture" name="numero_facture"
                                            placeholder="Numéro de facture">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="statut" class="small">Statut</label>
                                        <select class="form-select" id="statut" name="statut">
                                            <option value="validee">Validée</option>
                                            <option value="en_attente">En Attente</option>
                                            <option value="rejetee">Rejetée</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="observations" class="small">Observations</label>
                                        <textarea class="form-control" id="observations" name="observations" rows="2" placeholder="Observations..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Articles -->
                            <div class="mb-4" style="background: rgb(243, 246, 248); padding: 15px; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-bag"></i> Détails des Articles</h6>
                                <div id="articles-container">
                                    <div class="article-row mb-3 p-3"
                                        style="background: white; border: 1px solid #dee2e6; border-radius: 5px;">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="small">Article <span class="text-danger">*</span></label>
                                                <select class="form-select article-select" name="articles[]" required>
                                                    <option value="">-- Sélectionner un article --</option>
                                                    @foreach ($articles as $article)
                                                        <option value="{{ $article->id }}">{{ $article->designation }}
                                                            ({{ $article->code }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="small">Dépôt <span class="text-danger">*</span></label>
                                                <select class="form-select" name="depots[]" required>
                                                    <option value="">-- Sélectionner un dépôt --</option>
                                                    @foreach ($depots as $depot)
                                                        <option value="{{ $depot->id }}">{{ $depot->designation }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="small">Quantité <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="quantites[]"
                                                    placeholder="0" min="1" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="small">Prix vente <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control prix-unitaire"
                                                    name="prix_unitaires[]" placeholder="0.00" step="0.01"
                                                    min="0" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-sm btn-danger remove-article">
                                                    <i class="bi bi-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-article" class="btn btn-sm btn-success mb-3">
                                    <i class="bi bi-plus-circle"></i> Ajouter un Article
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i>&nbsp; Enregistrer la Sortie
                                </button>
                                <a href="{{ route('gestions_sorties.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>&nbsp; Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ajouter un article
        document.getElementById('add-article').addEventListener('click', function() {
            const container = document.getElementById('articles-container');
            const newRow = document.querySelector('.article-row').cloneNode(true);

            // Réinitialiser les valeurs
            newRow.querySelectorAll('input, select').forEach(el => {
                el.value = '';
            });

            container.appendChild(newRow);
            attachRemoveListener(newRow);
        });

        // Supprimer un article
        function attachRemoveListener(row) {
            row.querySelector('.remove-article').addEventListener('click', function() {
                if (document.querySelectorAll('.article-row').length > 1) {
                    row.remove();
                } else {
                    alert('Vous devez garder au moins un article');
                }
            });
        }

        // Attacher listener aux lignes existantes
        document.querySelectorAll('.article-row .remove-article').forEach(btn => {
            attachRemoveListener(btn.closest('.article-row'));
        });
    });
</script>
