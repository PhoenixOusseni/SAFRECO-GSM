@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Modifier la Vente {{ $vente->numero_vente }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_ventes.index') }}">Ventes</a></li>
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_ventes.show', $vente) }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>&nbsp; Retour
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Modifier la Vente</h5>

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <strong>Erreur!</strong> Veuillez corriger les erreurs suivantes:
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('gestions_ventes.update', $vente) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Informations générales -->
                            <div class="mb-4" style="background: rgb(232, 240, 243); padding: 15px; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-info-circle"></i> Informations de la Vente</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="numero_vente" class="small">Numéro de Vente</label>
                                        <input type="text" class="form-control" id="numero_vente"
                                               value="{{ $vente->numero_vente }}" disabled>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="date_vente" class="small">Date de Vente <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('date_vente') is-invalid @enderror"
                                               id="date_vente" name="date_vente"
                                               value="{{ old('date_vente', $vente->date_vente->format('Y-m-d')) }}" required>
                                        @error('date_vente')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="client_id" class="small">Client <span class="text-danger">*</span></label>
                                        <select class="form-select @error('client_id') is-invalid @enderror"
                                                id="client_id" name="client_id" required>
                                            <option value="">-- Sélectionner un client --</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}"
                                                    {{ old('client_id', $vente->client_id) == $client->id ? 'selected' : '' }}>
                                                    {{ $client->raison_sociale }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="numero_vehicule" class="small">Numéro de Véhicule</label>
                                        <input type="text" class="form-control @error('numero_vehicule') is-invalid @enderror"
                                               id="numero_vehicule" name="numero_vehicule"
                                               value="{{ old('numero_vehicule', $vente->numero_vehicule) }}"
                                               placeholder="Ex: AB-1234-CD">
                                        @error('numero_vehicule')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="chauffeur" class="small">Chauffeur</label>
                                        <input type="text" class="form-control @error('chauffeur') is-invalid @enderror"
                                               id="chauffeur" name="chauffeur"
                                               value="{{ old('chauffeur', $vente->chauffeur) }}"
                                               placeholder="Nom du chauffeur">
                                        @error('chauffeur')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="observation" class="small">Observation</label>
                                        <textarea class="form-control @error('observation') is-invalid @enderror"
                                                  id="observation" name="observation" rows="2"
                                                  placeholder="Observations...">{{ old('observation', $vente->observation) }}</textarea>
                                        @error('observation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Articles -->
                            <div class="mb-4" style="background: rgb(243, 246, 248); padding: 15px; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-bag"></i> Articles à Vendre</h6>
                                <div id="articles-container">
                                    @foreach($vente->details as $index => $detail)
                                        <div class="article-row mb-3 p-3"
                                             style="background: white; border: 1px solid #dee2e6; border-radius: 5px;">
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label class="small">Article <span class="text-danger">*</span></label>
                                                    <select class="form-select article-select" name="articles[{{ $index }}][article_id]" required>
                                                        <option value="">-- Sélectionner --</option>
                                                        @foreach($articles as $article)
                                                            <option value="{{ $article->id }}"
                                                                    data-prix="{{ $article->prix_vente }}"
                                                                    {{ $detail->article_id == $article->id ? 'selected' : '' }}>
                                                                {{ $article->designation }} ({{ $article->code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="small">Dépôt <span class="text-danger">*</span></label>
                                                    <select class="form-select depot-select" name="articles[{{ $index }}][depot_id]" required>
                                                        <option value="">-- Sélectionner --</option>
                                                        @foreach($depots as $depot)
                                                            <option value="{{ $depot->id }}"
                                                                    {{ $detail->depot_id == $depot->id ? 'selected' : '' }}>
                                                                {{ $depot->designation }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="small">Quantité <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control quantite-input"
                                                           name="articles[{{ $index }}][quantite]"
                                                           value="{{ $detail->quantite }}"
                                                           step="0.01" min="0.01" required>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="small">Prix Vente <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control prix-vente-input"
                                                           name="articles[{{ $index }}][prix_vente]"
                                                           value="{{ $detail->prix_vente }}"
                                                           step="0.01" min="0" required>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="small">Stock Dispo.</label>
                                                    <input type="text" class="form-control stock-disponible" readonly placeholder="0">
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

                            <!-- Boutons d'action -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i>&nbsp; Modifier la Vente
                                </button>
                                <a href="{{ route('gestions_ventes.show', $vente) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>&nbsp; Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let articleIndex = {{ $vente->details->count() }};

        // Ajouter un nouvel article
        document.getElementById('add-article').addEventListener('click', function() {
            const container = document.getElementById('articles-container');
            const newArticle = document.createElement('div');
            newArticle.className = 'article-row mb-3 p-3';
            newArticle.style.cssText = 'background: white; border: 1px solid #dee2e6; border-radius: 5px;';

            newArticle.innerHTML = `
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="small">Article <span class="text-danger">*</span></label>
                        <select class="form-select article-select" name="articles[${articleIndex}][article_id]" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($articles as $article)
                                <option value="{{ $article->id }}" data-prix="{{ $article->prix_vente }}">
                                    {{ $article->designation }} ({{ $article->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small">Dépôt <span class="text-danger">*</span></label>
                        <select class="form-select depot-select" name="articles[${articleIndex}][depot_id]" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot->id }}">{{ $depot->designation }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="small">Quantité <span class="text-danger">*</span></label>
                        <input type="number" class="form-control quantite-input"
                               name="articles[${articleIndex}][quantite]" placeholder="0" step="0.01" min="0.01" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="small">Prix Vente <span class="text-danger">*</span></label>
                        <input type="number" class="form-control prix-vente-input"
                               name="articles[${articleIndex}][prix_vente]" placeholder="0.00" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="small">Stock Dispo.</label>
                        <input type="text" class="form-control stock-disponible" readonly placeholder="0">
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

            container.appendChild(newArticle);
            articleIndex++;

            // Ajouter les événements aux nouveaux champs
            addArticleEvents(newArticle);
        });

        // Supprimer un article
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-article') || e.target.closest('.remove-article')) {
                const articlesCount = document.querySelectorAll('.article-row').length;
                if (articlesCount > 1) {
                    e.target.closest('.article-row').remove();
                } else {
                    alert('Vous devez avoir au moins un article dans la vente.');
                }
            }
        });

        // Fonction pour ajouter les événements à une ligne d'article
        function addArticleEvents(row) {
            const articleSelect = row.querySelector('.article-select');
            const depotSelect = row.querySelector('.depot-select');
            const stockInput = row.querySelector('.stock-disponible');
            const prixVenteInput = row.querySelector('.prix-vente-input');

            // Charger le prix de vente par défaut lors de la sélection d'un article
            articleSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const prixVente = selectedOption.getAttribute('data-prix');
                if (prixVente) {
                    prixVenteInput.value = prixVente;
                }
                checkStock(row);
            });

            // Vérifier le stock lors de la sélection du dépôt
            depotSelect.addEventListener('change', function() {
                checkStock(row);
            });

            // Charger le stock initial si les deux champs sont remplis
            if (articleSelect.value && depotSelect.value) {
                checkStock(row);
            }
        }

        // Vérifier le stock disponible
        function checkStock(row) {
            const articleId = row.querySelector('.article-select').value;
            const depotId = row.querySelector('.depot-select').value;
            const stockInput = row.querySelector('.stock-disponible');

            if (articleId && depotId) {
                fetch('{{ route("ventes.getStock") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        article_id: articleId,
                        depot_id: depotId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        stockInput.value = data.quantite_disponible;
                        stockInput.classList.remove('is-invalid');
                        stockInput.classList.add('is-valid');
                    } else {
                        stockInput.value = '0';
                        stockInput.classList.remove('is-valid');
                        stockInput.classList.add('is-invalid');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    stockInput.value = 'Erreur';
                });
            }
        }

        // Ajouter les événements aux lignes existantes
        document.querySelectorAll('.article-row').forEach(row => {
            addArticleEvents(row);
        });
    </script>
@endsection
