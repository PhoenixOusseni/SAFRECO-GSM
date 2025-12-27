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
                <h1>Nouveau Transfert</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_transferts.index') }}">Transferts</a></li>
                        <li class="breadcrumb-item active">Nouveau</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('gestions_transferts.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-plus-circle"></i> Formulaire de Transfert</h5>
                        <form action="{{ route('gestions_transferts.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small">Code</label>
                                    <input type="text" class="form-control" name="code" placeholder="Auto-généré" disabled >
                                </div>
                                <div class="col-md-6">
                                    <label for="date_transfert" class="small">Date de Transfert <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_transfert') is-invalid @enderror"
                                        id="date_transfert" name="date_transfert"
                                        value="{{ old('date_transfert', date('Y-m-d')) }}" required>
                                    @error('date_transfert')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="depot_source_id" class="small">Dépôt Source <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('depot_source_id') is-invalid @enderror"
                                        id="depot_source_id" name="depot_source_id" required>
                                        <option value="">Sélectionner le dépôt source</option>
                                        @foreach ($depots as $depot)
                                            <option value="{{ $depot->id }}"
                                                {{ old('depot_source_id') == $depot->id ? 'selected' : '' }}>
                                                {{ $depot->designation }} ({{ $depot->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('depot_source_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="depot_destination_id" class="small">Dépôt Destination <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('depot_destination_id') is-invalid @enderror"
                                        id="depot_destination_id" name="depot_destination_id" required>
                                        <option value="">Sélectionner le dépôt destination</option>
                                        @foreach ($depots as $depot)
                                            <option value="{{ $depot->id }}"
                                                {{ old('depot_destination_id') == $depot->id ? 'selected' : '' }}>
                                                {{ $depot->designation }} ({{ $depot->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('depot_destination_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="article_id" class="small">Article <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2-article @error('article_id') is-invalid @enderror" id="article_id"
                                        name="article_id" required>
                                        <option value="">Sélectionner un article</option>
                                        @foreach ($articles as $article)
                                            <option value="{{ $article->id }}"
                                                {{ old('article_id') == $article->id ? 'selected' : '' }}>
                                                {{ $article->designation }} ({{ $article->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('article_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="quantite" class="small">Quantité <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantite') is-invalid @enderror"
                                        id="quantite" name="quantite" value="{{ old('quantite') }}" step="0.01"
                                        min="0.01" required>
                                    <div id="stock-info" class="form-text"></div>
                                    @error('quantite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="numero_vehicule" class="small">Numéro de Véhicule</label>
                                    <input type="text"
                                        class="form-control @error('numero_vehicule') is-invalid @enderror"
                                        id="numero_vehicule" name="numero_vehicule" value="{{ old('numero_vehicule') }}"
                                        placeholder="Ex: AB-1234-CD">
                                    @error('numero_vehicule')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nom_chauffeur" class="small">Nom du Chauffeur</label>
                                    <input type="text" class="form-control @error('nom_chauffeur') is-invalid @enderror"
                                        id="nom_chauffeur" name="nom_chauffeur" value="{{ old('nom_chauffeur') }}"
                                        placeholder="Ex: Jean Dupont">
                                    @error('nom_chauffeur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="observation" class="small">Observation</label>
                                    <textarea class="form-control @error('observation') is-invalid @enderror" id="observation" name="observation"
                                        rows="3" placeholder="Remarques ou observations sur ce transfert...">{{ old('observation') }}</textarea>
                                    @error('observation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Enregistrer le Transfert
                                    </button>
                                    <a href="{{ route('gestions_transferts.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Annuler
                                    </a>
                                </div>
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
                                    text: a.designation + ' (' + a.code + ')'
                                };
                            })
                        };
                    }
                }
            });
        }

        $(document).ready(function() {
            // Initialiser Select2
            initSelect2Article();

            const depotSourceSelect = document.getElementById('depot_source_id');
            const articleSelect = document.getElementById('article_id');
            const stockInfo = document.getElementById('stock-info');

            function updateStockInfo() {
                const depotSourceId = depotSourceSelect.value;
                const articleId = articleSelect.value;

                if (depotSourceId && articleId) {
                    fetch(`/api/stock-disponible?depot_id=${depotSourceId}&article_id=${articleId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                stockInfo.innerHTML =
                                    `<span class="text-success"><i class="bi bi-info-circle"></i> Stock disponible: <strong>${data.quantite_disponible}</strong></span>`;
                            } else {
                                stockInfo.innerHTML =
                                    `<span class="text-danger"><i class="bi bi-exclamation-triangle"></i> ${data.message}</span>`;
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            stockInfo.innerHTML = '';
                        });
                } else {
                    stockInfo.innerHTML = '';
                }
            }

            depotSourceSelect.addEventListener('change', updateStockInfo);

            // Écouter les changements de Select2
            $(articleSelect).on('select2:select', function() {
                updateStockInfo();
            });

            // Vérifier au chargement si les valeurs sont déjà sélectionnées
            if (depotSourceSelect.value && articleSelect.value) {
                updateStockInfo();
            }
        });
    </script>
@endsection
