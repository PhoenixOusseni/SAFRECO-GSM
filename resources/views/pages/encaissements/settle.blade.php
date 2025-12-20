@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Encaissements</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_encaissements.index') }}">Encaissements</a></li>
                        <li class="breadcrumb-item active">Solder</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_encaissements.show', $encaissement->id) }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-9 offset-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-cash-coin"></i> Solder un Encaissement
                        </h5>

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

                        <!-- Infos de la vente -->
                        <div class="mb-4 p-3" style="background: #f8f9fa; border-radius: 5px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>N° Vente:</strong> {{ $encaissement->vente->numero_vente }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Client:</strong> {{ $encaissement->vente->client->raison_sociale ?? $encaissement->vente->client->nom }}
                                    </p>
                                    <p class="mb-0">
                                        <strong>Date Vente:</strong> {{ \Carbon\Carbon::parse($encaissement->vente->date_vente)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Mode Paiement Initial:</strong>
                                        <span class="badge bg-primary">{{ $encaissement->mode_paiement }}</span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Date Encaissement:</strong> {{ \Carbon\Carbon::parse($encaissement->date_encaissement)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Barre de progression -->
                        <div class="mb-4">
                            <h6>État du Paiement</h6>
                            <div class="progress" style="height: 30px;">
                                @php
                                    $montant_total = $encaissement->vente->montant_total;
                                    $montant_encaisse = $encaissement->montant_encaisse;
                                    $pourcentage = ($montant_encaisse / $montant_total) * 100;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ $pourcentage }}%;"
                                     aria-valuenow="{{ $pourcentage }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($pourcentage, 1) }}% ({{ number_format($montant_encaisse, 2, ',', ' ') }} F)
                                </div>
                            </div>
                        </div>

                        <!-- Résumé des montants -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-muted">Montant Total</h6>
                                        <h4 class="text-primary mb-0">{{ number_format($montant_total, 2, ',', ' ') }} F CFA</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-muted">Déjà Encaissé</h6>
                                        <h4 class="text-success mb-0">{{ number_format($montant_encaisse, 2, ',', ' ') }} F CFA</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-muted">Reste à Payer</h6>
                                        <h4 class="text-warning mb-0">{{ number_format($reste, 2, ',', ' ') }} F CFA</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire de solde -->
                        <form action="{{ route('gestions_encaissements.processSettle', $encaissement->id) }}" method="POST" id="settleForm">
                            @csrf

                            <div class="mb-4" style="background: rgb(232, 240, 243); padding: 15px; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-info-circle"></i> Informations du Solde</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="date_solde" class="small">Date du Solde <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('date_solde') is-invalid @enderror"
                                               id="date_solde" name="date_solde"
                                               value="{{ old('date_solde', date('Y-m-d')) }}" required>
                                        @error('date_solde')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mode_paiement_solde" class="small">Mode de Paiement <span class="text-danger">*</span></label>
                                        <select class="form-select @error('mode_paiement_solde') is-invalid @enderror"
                                                id="mode_paiement_solde" name="mode_paiement_solde" required>
                                            <option value="">-- Sélectionner --</option>
                                            <option value="Espèces" {{ old('mode_paiement_solde') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                            <option value="Chèque" {{ old('mode_paiement_solde') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                            <option value="Virement" {{ old('mode_paiement_solde') == 'Virement' ? 'selected' : '' }}>Virement</option>
                                            <option value="Carte bancaire" {{ old('mode_paiement_solde') == 'Carte bancaire' ? 'selected' : '' }}>Carte bancaire</option>
                                            <option value="Mobile Money" {{ old('mode_paiement_solde') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                                        </select>
                                        @error('mode_paiement_solde')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="montant_solde" class="small">Montant à Solder <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control @error('montant_solde') is-invalid @enderror"
                                                   id="montant_solde" name="montant_solde"
                                                   value="{{ old('montant_solde', number_format($reste, 2, '.')) }}"
                                                   step="0.01" min="0.01" max="{{ $reste }}" required>
                                            <span class="input-group-text">F CFA</span>
                                            @error('montant_solde')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="form-text text-muted d-block mt-2">
                                            <i class="bi bi-info-circle"></i> Maximum à solder: <strong>{{ number_format($reste, 2, ',', ' ') }} F CFA</strong>
                                        </small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="banque_id" class="small">Banque</label>
                                        <select class="form-select" name="banque_id" id="banque_id">
                                            <option value="">-- Sélectionner une banque --</option>
                                            @foreach(App\Models\Banque::all() as $banque)
                                                <option value="{{ $banque->id }}">{{ $banque->designation }} - {{ $banque->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="caisse_id" class="small">Caisse</label>
                                        <select class="form-select" name="caisse_id" id="caisse_id">
                                            <option value="">-- Sélectionner une caisse --</option>
                                            @foreach(App\Models\Caisse::all() as $caisse)
                                                <option value="{{ $caisse->id }}">{{ $caisse->designation }} - {{ $caisse->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="reference" class="small">Référence</label>
                                        <input type="text" class="form-control" id="reference" name="reference" value="{{ old('reference') }}" placeholder="Référence du paiement">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="notes" class="small">Remarques (Optionnel)</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                                  id="notes" name="notes" rows="3"
                                                  placeholder="Ajouter des notes ou observations...">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Résumé après solde -->
                            <div class="mb-4 p-3" style="background: #e8f5e9; border-left: 4px solid #28a745; border-radius: 5px;">
                                <h6 class="mb-3"><i class="bi bi-check-circle"></i> Après le Solde</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Montant Total Encaissé:</strong>
                                            <span id="total_after">{{ number_format($montant_encaisse, 2, ',', ' ') }}</span> F CFA
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-0">
                                            <strong>Nouveau Reste:</strong>
                                            <span id="reste_after">{{ number_format($reste, 2, ',', ' ') }}</span> F CFA
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i>&nbsp; Confirmer le Solde
                                </button>
                                <a href="{{ route('gestions_encaissements.show', $encaissement->id) }}" class="btn btn-secondary">
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
        $(document).ready(function() {
            const montantTotal = {{ $montant_total }};
            const montantEncaisse = {{ $montant_encaisse }};

            // Mettre à jour les montants en temps réel
            $('#montant_solde').on('input', function() {
                const montantSolde = parseFloat($(this).val()) || 0;
                const totalApres = montantEncaisse + montantSolde;
                const resteApres = montantTotal - totalApres;

                $('#total_after').text(totalApres.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('#reste_after').text(resteApres.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            });

            // Validation avant soumission
            $('#settleForm').on('submit', function(e) {
                const montantSolde = parseFloat($('#montant_solde').val()) || 0;
                const reste = {{ $reste }};

                if (montantSolde > reste) {
                    e.preventDefault();
                    alert('Le montant à solder ne peut pas dépasser le reste dû (' + reste.toFixed(2) + ' F CFA).');
                    return false;
                }

                if (montantSolde <= 0) {
                    e.preventDefault();
                    alert('Le montant à solder doit être supérieur à 0.');
                    return false;
                }
            });
        });
    </script>
@endsection
