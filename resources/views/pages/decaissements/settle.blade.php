@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Solder le Décaissement</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_decaissements.index') }}">Décaissements</a></li>
                        <li class="breadcrumb-item active">Solder</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Solde du Décaissement</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading">Erreurs de validation</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Informations du décaissement -->
                        <div class="alert alert-info mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>N° Achat:</strong> {{ $decaissement->achat->numero_achat }}<br>
                                    <strong>Fournisseur:</strong> {{ $decaissement->achat->fournisseur->nom ?? 'N/A' }}<br>
                                    <strong>Montant Total:</strong>
                                    <span class="text-primary fw-bold">{{ number_format($decaissement->montant, 0, ',', ' ') }}
                                        FCFA</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Montant Décaissé:</strong>
                                    <span class="text-success fw-bold">{{ number_format($decaissement->montant_decaisse, 0, ',', ' ') }}
                                        FCFA</span><br>
                                    <strong>Reste à Décaisser:</strong>
                                    <span class="text-warning fw-bold">{{ number_format($decaissement->reste, 0, ',', ' ') }}
                                        FCFA</span><br>
                                    <strong>Progression:</strong>
                                    @php
                                        $percentage = ($decaissement->montant_decaisse / $decaissement->montant) * 100;
                                    @endphp
                                    {{ number_format($percentage, 2, ',', ' ') }}%
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('gestions_decaissements.processSettle', $decaissement->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Date de Solde *</label>
                                        <input type="date" class="form-control @error('date_solde') is-invalid @enderror"
                                            name="date_solde" value="{{ old('date_solde', date('Y-m-d')) }}" required>
                                        @error('date_solde')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mode de Paiement</label>
                                        <select class="form-control @error('mode_paiement') is-invalid @enderror"
                                            name="mode_paiement">
                                            <option value="">Sélectionner le mode</option>
                                            <option value="Espèces"
                                                {{ $decaissement->mode_paiement == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                            <option value="Chèque"
                                                {{ $decaissement->mode_paiement == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                            <option value="Virement"
                                                {{ $decaissement->mode_paiement == 'Virement' ? 'selected' : '' }}>Virement</option>
                                            <option value="Carte"
                                                {{ $decaissement->mode_paiement == 'Carte' ? 'selected' : '' }}>Carte</option>
                                            <option value="Mobile Money"
                                                {{ $decaissement->mode_paiement == 'Mobile Money' ? 'selected' : '' }}>Mobile
                                                Money
                                            </option>
                                        </select>
                                        @error('mode_paiement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Montant à Décaisser *</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('montant_solde') is-invalid @enderror"
                                            id="montant_solde" name="montant_solde" value="{{ old('montant_solde', $decaissement->reste) }}"
                                            max="{{ $decaissement->reste }}" required>
                                        <small class="form-text text-muted">
                                            Montant maximum: {{ number_format($decaissement->reste, 0, ',', ' ') }} FCFA
                                        </small>
                                        @error('montant_solde')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nouveau Reste</label>
                                        <input type="number" class="form-control" id="nouveau_reste" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observation</label>
                                <textarea class="form-control" name="observation" rows="3"
                                    placeholder="Ajoutez une observation si nécessaire">{{ old('observation') }}</textarea>
                            </div>

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> <strong>Attention:</strong> Vérifiez que le montant à
                                décaisser ne dépasse pas le reste à payer.
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Enregistrer le Solde
                                    </button>
                                    <a href="{{ route('gestions_decaissements.show', $decaissement->id) }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            const resteInitial = {{ $decaissement->reste }};
            const montantSoldeInput = $('#montant_solde');
            const nouveauResteInput = $('#nouveau_reste');

            // Calculer le nouveau reste en temps réel
            montantSoldeInput.on('input', function() {
                const montantSolde = parseFloat($(this).val()) || 0;
                const nouveauReste = Math.max(0, resteInitial - montantSolde);
                nouveauResteInput.val(nouveauReste.toFixed(2));
            });

            // Initialiser le nouveau reste
            const montantInitial = parseFloat(montantSoldeInput.val()) || 0;
            const nouveauResteInitial = Math.max(0, resteInitial - montantInitial);
            nouveauResteInput.val(nouveauResteInitial.toFixed(2));
        });
    </script>
@endsection
