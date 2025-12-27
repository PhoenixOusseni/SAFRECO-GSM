@extends('layouts.master')

@section('content')
    <!-- CDN Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            height: 38px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }
    </style>
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Sorties de Stock</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_sorties.index') }}">Sorties</a></li>
                        <li class="breadcrumb-item active">Modifier la Sortie</li>
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
                        <h5 class="card-title">Modifier la Sortie - {{ $sortie->numero_sortie }}</h5>
                        <form action="{{ route('gestions_sorties.update', $sortie->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <!-- En-tête Sortie -->
                            <div class="mb-4" style="background: rgb(232, 240, 243); padding: 15px; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-box-seam"></i> Informations de la Sortie</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="numero_sortie" class="small">Numéro Sortie</label>
                                        <input type="text" class="form-control" id="numero_sortie" name="numero_sortie" value="{{ $sortie->numero_sortie }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="date_sortie" class="small">Date Sortie <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date_sortie" name="date_sortie" value="{{ old('date_sortie', $sortie->date_sortie->format('Y-m-d')) }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="type_sortie" class="small">Type de Sortie <span class="text-danger">*</span></label>
                                        <select class="form-select" id="type_sortie" name="type_sortie" required>
                                            <option value="">-- Sélectionner un type --</option>
                                            <option value="vente" {{ old('type_sortie', $sortie->type_sortie) == 'vente' ? 'selected' : '' }}>Vente</option>
                                            <option value="transfert" {{ old('type_sortie', $sortie->type_sortie) == 'transfert' ? 'selected' : '' }}>Transfert</option>
                                            <option value="destruction" {{ old('type_sortie', $sortie->type_sortie) == 'destruction' ? 'selected' : '' }}>Destruction</option>
                                            <option value="inventaire" {{ old('type_sortie', $sortie->type_sortie) == 'inventaire' ? 'selected' : '' }}>Inventaire</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="client_id" class="small">Client</label>
                                        <select class="form-select" id="client_id" name="client_id">
                                            <option value="">-- Sélectionner un client --</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" {{ old('client_id', $sortie->client_id) == $client->id ? 'selected' : '' }}>
                                                    {{ $client->nom ?? $client->raison_sociale }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="numero_facture" class="small">Numéro Facture</label>
                                        <input type="text" class="form-control" id="numero_facture" name="numero_facture" value="{{ old('numero_facture', $sortie->numero_facture) }}" placeholder="Numéro de facture">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="statut" class="small">Statut</label>
                                        <select class="form-control" id="statut" name="statut">
                                            <option value="validee" {{ old('statut', $sortie->statut) == 'validee' ? 'selected' : '' }}>Validée</option>
                                            <option value="en_attente" {{ old('statut', $sortie->statut) == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                                            <option value="rejetee" {{ old('statut', $sortie->statut) == 'rejetee' ? 'selected' : '' }}>Rejetée</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="observations" class="small">Observations</label>
                                        <textarea class="form-control" id="observations" name="observations" rows="2" placeholder="Observations...">{{ old('observations', $sortie->observations) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Articles -->
                            <div class="mb-4" style="background: rgb(243, 246, 248); padding: 15px; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-bag"></i> Détails des Articles</h6>

                                <div id="articles-container">
                                    @foreach($sortie->details as $index => $detail)
                                    <div class="article-row mb-3 p-3" style="background: white; border: 1px solid #dee2e6; border-radius: 5px;">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="small">Article <span class="text-danger">*</span></label>
                                                <select class="form-select select2-article article-select" name="articles[]" required>
                                                    <option value="">-- Sélectionner un article --</option>
                                                    @foreach($articles as $article)
                                                        <option value="{{ $article->id }}" {{ $detail->article_id == $article->id ? 'selected' : '' }}>
                                                            {{ $article->designation }} ({{ $article->code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="small">Dépôt <span class="text-danger">*</span></label>
                                                <select class="form-select select2-depot depot-select" name="depots[]" required>
                                                    <option value="">-- Sélectionner un dépôt --</option>
                                                    @foreach($depots as $depot)
                                                        <option value="{{ $depot->id }}" {{ $detail->depot_id == $depot->id ? 'selected' : '' }}>
                                                            {{ $depot->designation }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="small">Quantité <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="quantites[]" value="{{ $detail->quantite }}" placeholder="0" min="1" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="small">Prix Vente <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control prix-vente" name="prix_vente[]" value="{{ $detail->prix_vente }}" placeholder="0.00" step="0.01" min="0" required>
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
                                    @endforeach
                                </div>

                                <button type="button" id="add-article" class="btn btn-sm btn-success mb-3">
                                    <i class="bi bi-plus-circle"></i> Ajouter un Article
                                </button>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i>&nbsp; Mettre à jour la Sortie
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

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialiser Select2 pour client
    function initSelect2Client(el) {
        $(el || '.select2-client').select2({
            width: '100%',
            placeholder: 'Rechercher un client...',
            allowClear: true,
            ajax: {
                url: '{{ route("clients.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { search: params.term }; },
                processResults: function(data) {
                    return {
                        results: data.map(function(c) {
                            return { id: c.id, text: c.raison_sociale || c.nom };
                        })
                    };
                },
                cache: true
            }
        });
    }

    // Initialiser Select2 pour article
    function initSelect2Article(el) {
        $(el || '.select2-article').select2({
            width: '100%',
            placeholder: 'Rechercher un article...',
            allowClear: true,
            ajax: {
                url: '{{ route("articles.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { search: params.term }; },
                processResults: function(data) {
                    return {
                        results: data.map(function(a) {
                            return {
                                id: a.id,
                                text: a.designation + ' (' + a.code + ')',
                                prix: a.prix_vente
                            };
                        })
                    };
                }
            }
        });
    }

    // Initialiser Select2 pour dépôt
    function initSelect2Depot(el) {
        $(el || '.select2-depot').select2({
            width: '100%',
            placeholder: 'Sélectionner un dépôt...',
            ajax: {
                url: '{{ route("depots.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { search: params.term }; },
                processResults: function(data) {
                    return {
                        results: data.map(function(d) {
                            return { id: d.id, text: d.designation };
                        })
                    };
                }
            }
        });
    }

    $(document).ready(function() {
        let articleIndex = {{ $sortie->details->count() }};

        // Initialiser Select2
        initSelect2Client();
        initSelect2Article();
        initSelect2Depot();

        // Ajouter un article
        document.getElementById('add-article').addEventListener('click', function() {
            const container = document.getElementById('articles-container');
            const newRow = document.createElement('div');
            newRow.className = 'article-row mb-3 p-3';
            newRow.style.cssText = 'background: white; border: 1px solid #dee2e6; border-radius: 5px;';

            newRow.innerHTML = `
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="small">Article <span class="text-danger">*</span></label>
                        <select class="form-select select2-article article-select" name="articles[]" required>
                            <option value="">-- Sélectionner un article --</option>
                            @foreach($articles as $article)
                                <option value="{{ $article->id }}">{{ $article->designation }} ({{ $article->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small">Dépôt <span class="text-danger">*</span></label>
                        <select class="form-select select2-depot depot-select" name="depots[]" required>
                            <option value="">-- Sélectionner un dépôt --</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot->id }}">{{ $depot->designation }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="small">Quantité <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="quantites[]" placeholder="0" min="1" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small">Prix Vente <span class="text-danger">*</span></label>
                        <input type="number" class="form-control prix-vente" name="prix_vente[]" placeholder="0.00" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-sm btn-danger remove-article">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(newRow);

            // Initialiser Select2 sur la nouvelle ligne
            initSelect2Article(newRow.querySelector('.select2-article'));
            initSelect2Depot(newRow.querySelector('.select2-depot'));

            attachRemoveListener(newRow);
            articleIndex++;
        });

        // Supprimer un article
        function attachRemoveListener(row) {
            row.querySelector('.remove-article').addEventListener('click', function() {
                if (document.querySelectorAll('.article-row').length > 1) {
                    $(row).find('.select2-article, .select2-depot').select2('destroy');
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
@endsection
