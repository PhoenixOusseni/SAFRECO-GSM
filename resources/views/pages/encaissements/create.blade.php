@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Encaissements</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_encaissements.index') }}">Encaissements</a>
                        </li>
                        <li class="breadcrumb-item active">Nouvel Encaissement</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_encaissements.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Enregistrer un Nouvel Encaissement</h5>

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <strong>Erreur!</strong> Veuillez corriger les
                                erreurs suivantes:
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('gestions_encaissements.store') }}" method="POST" id="encaissementForm">
                            @csrf

                            <div class="mb-4" style="background: rgb(232, 240, 243); padding: 15px; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-info-circle"></i> Informations de l'Encaissement</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="date_encaissement" class="small">Date d'Encaissement <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('date_encaissement') is-invalid @enderror"
                                            id="date_encaissement" name="date_encaissement"
                                            value="{{ old('date_encaissement', date('Y-m-d')) }}" required>
                                        @error('date_encaissement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mode_paiement" class="small">Mode de Paiement</label>
                                        <select class="form-select @error('mode_paiement') is-invalid @enderror"
                                            id="mode_paiement" name="mode_paiement">
                                            <option value="">-- Selectionner --</option>
                                            <option value="Espèces"
                                                {{ old('mode_paiement') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                            <option value="Chèque" {{ old('mode_paiement') == 'Chèque' ? 'selected' : '' }}>
                                                Chèque</option>
                                            <option value="Virement"
                                                {{ old('mode_paiement') == 'Virement' ? 'selected' : '' }}>Virement
                                            </option>
                                            <option value="Carte bancaire"
                                                {{ old('mode_paiement') == 'Carte bancaire' ? 'selected' : '' }}>Carte
                                                bancaire</option>
                                            <option value="Mobile Money"
                                                {{ old('mode_paiement') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money
                                            </option>
                                        </select>
                                        @error('mode_paiement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="vente_id" class="small">Vente <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('vente_id') is-invalid @enderror" id="vente_id"
                                            name="vente_id" style="width: 100%;" required>
                                            <option></option>
                                        </select>
                                        @error('vente_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted d-block mt-2">
                                            <i class="bi bi-info-circle"></i> Tapez pour rechercher par numéro de vente ou
                                            nom du client
                                        </small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="client_nom" class="small">Client</label>
                                        <input type="text" class="form-control" id="client_nom" readonly
                                            placeholder="Sélectionner une vente d'abord">
                                        <input type="hidden" id="client_id" name="client_id">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="montant" class="small">Montant Total de la Vente <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('montant') is-invalid @enderror"
                                            id="montant" name="montant" step="0.01" min="0" readonly required>
                                        @error('montant')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="montant_encaisse" class="small">Montant Encaissé <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('montant_encaisse') is-invalid @enderror"
                                            id="montant_encaisse" name="montant_encaisse" value="{{ old('montant_encaisse') }}" step="0.01" min="0" required>
                                        @error('montant_encaisse')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="reste" class="small">Reste à Payer</label>
                                        <input type="number" class="form-control" id="reste" step="0.01"
                                            readonly placeholder="0.00">
                                        <small class="form-text text-muted">
                                            <i class="bi bi-calculator"></i> Calculé automatiquement: Montant Total -
                                            Montant Encaissé
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i>&nbsp; Enregistrer l'Encaissement
                                </button>
                                <a href="{{ route('gestions_encaissements.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>&nbsp; Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialiser Select2 avec AJAX
            $('#vente_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Rechercher une vente...',
                allowClear: true,
                ajax: {
                    url: '{{ route('encaissements.searchVentes') }}',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term || ''
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results || []
                        };
                    }
                },
                minimumInputLength: 0,
                templateResult: formatVenteResult,
                templateSelection: formatVenteSelection,
                language: {
                    searching: function() {
                        return 'Recherche...';
                    }
                }
            });

            // Formater l'affichage des résultats
            function formatVenteResult(vente) {
                if (!vente.id) return vente.text;
                return $(`
                    <div class="d-flex justify-content-between">
                        <span>${vente.text}</span>
                        <span class="text-muted small">${parseFloat(vente.montant_total).toFixed(2)} F CFA</span>
                    </div>
                `);
            }

            // Formater la sélection
            function formatVenteSelection(vente) {
                return vente.text || 'Rechercher une vente...';
            }

            // Quand une vente est sélectionnée
            $('#vente_id').on('select2:select', function(e) {
                const vente = e.params.data;

                // Remplir les champs automatiquement
                $('#client_id').val(vente.client_id);
                $('#client_nom').val(vente.client_nom);
                $('#montant').val(parseFloat(vente.montant_total).toFixed(2));

                // Calculer le reste
                calculateReste();
            });

            // Réinitialiser les champs si la sélection est effacée
            $('#vente_id').on('select2:clear', function() {
                $('#client_id').val('');
                $('#client_nom').val('');
                $('#montant').val('');
                $('#montant_encaisse').val('');
                $('#reste').val('');
            });

            // Calculer le reste quand le montant encaissé change
            $('#montant_encaisse').on('input', function() {
                calculateReste();
            });

            // Fonction pour calculer le reste
            function calculateReste() {
                const montant = parseFloat($('#montant').val()) || 0;
                const montantEncaisse = parseFloat($('#montant_encaisse').val()) || 0;
                const reste = montant - montantEncaisse;

                $('#reste').val(reste.toFixed(2));

                // Ajouter une classe visuelle si le reste est négatif
                if (reste < 0) {
                    $('#reste').addClass('is-invalid');
                    $('#montant_encaisse').addClass('is-invalid');
                } else {
                    $('#reste').removeClass('is-invalid');
                    $('#montant_encaisse').removeClass('is-invalid');
                }
            }

            // Validation avant soumission
            $('#encaissementForm').on('submit', function(e) {
                const venteId = $('#vente_id').val();
                const montant = parseFloat($('#montant').val()) || 0;
                const montantEncaisse = parseFloat($('#montant_encaisse').val()) || 0;

                if (!venteId) {
                    e.preventDefault();
                    alert('Veuillez sélectionner une vente');
                    return false;
                }

                if (montantEncaisse > montant) {
                    e.preventDefault();
                    alert('Le montant encaissé ne peut pas être supérieur au montant total de la vente.');
                    return false;
                }

                if (montantEncaisse <= 0) {
                    e.preventDefault();
                    alert('Le montant encaissé doit être supérieur à 0.');
                    return false;
                }
            });
        });
    </script>
@endsection
