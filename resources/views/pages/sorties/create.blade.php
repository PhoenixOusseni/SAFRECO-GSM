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
                        <li class="breadcrumb-item active">Gestion des Sorties</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_sorties.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
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
                                        <select class="form-select select2-client" id="client_id" name="client_id">
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
                            </div>
                            <!-- Articles -->
                            <div class="mb-4" style="background: rgb(243, 246, 248); padding: 15px; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-bag"></i> Détails des Articles</h6>
                                <div id="articles-container">
                                    <div class="article-row mb-3 p-3"
                                        style="background: white; border: 1px solid #dee2e6; border-radius: 5px;">
                                        <div class="row align-items-end">
                                            <div class="col-md-2 mb-3">
                                                <label class="small">Scanner Code-barres</label>
                                                <input type="text" class="form-control barcode-scanner" placeholder="Scanner ici..." autocomplete="off">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="small">Article <span class="text-danger">*</span></label>
                                                <select class="form-select select2-article article-select" name="articles[]" required>
                                                    <option value="">-- Sélectionner un article --</option>
                                                    @foreach ($articles as $article)
                                                        <option value="{{ $article->id }}" data-prix-vente="{{ $article->prix_vente }}">{{ $article->designation }}
                                                            ({{ $article->code }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="small">Dépôt <span class="text-danger">*</span></label>
                                                <select class="form-select select2-depot depot-select" name="depots[]" required>
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
                                            <div class="col-md-2 mb-3">
                                                <label class="small">Prix vente <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control prix-unitaire"
                                                    name="prix_unitaires[]" placeholder="0.00" step="0.01"
                                                    min="0" required>
                                            </div>
                                            <div class="col-md-2 mb-3 d-flex gap-2">
                                                <button type="button" class="btn btn-success add-article-inline">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger remove-article">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i>&nbsp; Enregistrer la Sortie
                                </button>
                                <a href="{{ route('gestions_sorties.index') }}" class="btn btn-danger">
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
                                prix_achat: a.prix_achat,
                                prix_vente: a.prix_vente,
                                code_barre: a.code_barre
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

    // Fonction pour rechercher un article par code-barres
    function searchArticleByBarcode(barcode, row) {
        $.ajax({
            url: '{{ route("articles.search") }}',
            type: 'GET',
            data: { search: barcode },
            success: function(articles) {
                if (articles.length > 0) {
                    const article = articles[0];
                    const select = $(row).find('.article-select');

                    // Créer une nouvelle option et la sélectionner
                    const newOption = new Option(article.designation + ' (' + article.code + ')', article.id, true, true);
                    $(newOption).data('prix-vente', article.prix_vente);
                    select.append(newOption).trigger('change');

                    // Remplir le prix de vente
                    $(row).find('.prix-unitaire').val(article.prix_vente);

                    // Focus sur la quantité
                    $(row).find('input[name="quantites[]"]').focus();
                } else {
                    alert('Aucun article trouvé avec ce code-barres');
                    $(row).find('.barcode-scanner').val('');
                }
            }
        });
    }

    // Fonction pour attacher le scanner de code-barres
    function attachBarcodeScanner(row) {
        const scanner = $(row).find('.barcode-scanner');
        let barcodeBuffer = '';
        let lastInputTime = 0;

        scanner.on('keypress', function(e) {
            if (e.which === 13) { // Touche Entrée
                e.preventDefault();
                const barcode = $(this).val().trim();
                if (barcode.length > 0) {
                    searchArticleByBarcode(barcode, row);
                    $(this).val('');
                }
                barcodeBuffer = '';
            }
        });

        // Détection automatique du scan (rapide succession de caractères)
        scanner.on('input', function() {
            const currentTime = new Date().getTime();
            if (currentTime - lastInputTime < 100) {
                barcodeBuffer += $(this).val();
            } else {
                barcodeBuffer = $(this).val();
            }
            lastInputTime = currentTime;

            clearTimeout(scanner.data('scanTimeout'));
            scanner.data('scanTimeout', setTimeout(function() {
                const barcode = scanner.val().trim();
                if (barcode.length >= 8) {
                    searchArticleByBarcode(barcode, row);
                    scanner.val('');
                }
            }, 100));
        });
    }

    // Fonction pour gérer le changement d'article
    function attachArticleChangeHandler(row) {
        $(row).find('.article-select').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const prixVente = selectedOption.data('prix-vente');
            if (prixVente) {
                $(row).find('.prix-unitaire').val(prixVente);
            }
        });
    }

    $(document).ready(function() {
        // Initialiser Select2 pour le client
        initSelect2Client();

        // Initialiser Select2 sur la première ligne existante
        $('.article-row').each(function() {
            initSelect2Article($(this).find('.select2-article'));
            initSelect2Depot($(this).find('.select2-depot'));
            attachBarcodeScanner(this);
            attachArticleChangeHandler(this);
        });

        // Fonction pour ajouter un article
        function addArticleRow() {
            const container = document.getElementById('articles-container');
            const newRow = document.querySelector('.article-row').cloneNode(true);

            // Réinitialiser les valeurs
            newRow.querySelectorAll('input, select').forEach(el => {
                el.value = '';
            });

            container.appendChild(newRow);

            // Initialiser Select2 sur la nouvelle ligne
            initSelect2Article(newRow.querySelector('.select2-article'));
            initSelect2Depot(newRow.querySelector('.select2-depot'));

            // Attacher les événements
            attachBarcodeScanner(newRow);
            attachArticleChangeHandler(newRow);
            attachEventListeners(newRow);
        }

        // Fonction pour supprimer un article
        function removeArticleRow(row) {
            if (document.querySelectorAll('.article-row').length > 1) {
                $(row).find('.select2-article, .select2-depot').select2('destroy');
                row.remove();
            } else {
                alert('Vous devez garder au moins un article');
            }
        }

        // Attacher les événements (Ajouter et Supprimer)
        function attachEventListeners(row) {
            // Bouton Ajouter
            row.querySelector('.add-article-inline').addEventListener('click', function() {
                addArticleRow();
            });

            // Bouton Supprimer
            row.querySelector('.remove-article').addEventListener('click', function() {
                removeArticleRow(row);
            });
        }

        // Attacher les événements aux lignes existantes
        document.querySelectorAll('.article-row').forEach(row => {
            attachEventListeners(row);
        });
    });
</script>
