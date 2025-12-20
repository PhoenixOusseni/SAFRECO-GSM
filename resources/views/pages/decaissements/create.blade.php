@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Nouveau Décaissement</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_decaissements.index') }}">Décaissements</a>
                        </li>
                        <li class="breadcrumb-item active">Créer</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_decaissements.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Créer un nouveau Décaissement</h5>
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

                        <form action="{{ route('gestions_decaissements.store') }}" method="POST">
                            @csrf

                            <div class="mb-4" style="background: rgb(232, 240, 243); padding: 15px; border-radius: 5px;">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="small">Date Décaissement *</label>
                                            <input type="date"
                                                class="form-control @error('date_decaissement') is-invalid @enderror"
                                                name="date_decaissement"
                                                value="{{ old('date_decaissement', date('Y-m-d')) }}" required>
                                            @error('date_decaissement')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="small">Mode de Paiement</label>
                                            <select class="form-select @error('mode_paiement') is-invalid @enderror"
                                                name="mode_paiement">
                                                <option value="">Sélectionner le mode</option>
                                                <option value="Espèces"
                                                    {{ old('mode_paiement') == 'Espèces' ? 'selected' : '' }}>
                                                    Espèces</option>
                                                <option value="Chèque"
                                                    {{ old('mode_paiement') == 'Chèque' ? 'selected' : '' }}>
                                                    Chèque
                                                </option>
                                                <option value="Virement"
                                                    {{ old('mode_paiement') == 'Virement' ? 'selected' : '' }}>
                                                    Virement</option>
                                                <option value="Carte"
                                                    {{ old('mode_paiement') == 'Carte' ? 'selected' : '' }}>
                                                    Carte
                                                </option>
                                                <option value="Mobile Money"
                                                    {{ old('mode_paiement') == 'Mobile Money' ? 'selected' : '' }}>Mobile
                                                    Money
                                                </option>
                                            </select>
                                            @error('mode_paiement')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="small">Achat *</label>
                                            <select class="form-control select2 @error('achat_id') is-invalid @enderror"
                                                id="achat_select" name="achat_id" required>
                                                <option value="">Sélectionner un achat</option>
                                                @foreach ($achats as $achat)
                                                    <option value="{{ $achat->id }}"
                                                        {{ old('achat_id') == $achat->id ? 'selected' : '' }}>
                                                        {{ $achat->numero_achat }} -
                                                        {{ $achat->fournisseur->nom ?? 'N/A' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('achat_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="small">Fournisseur</label>
                                            <input type="text" class="form-control" id="fournisseur_nom" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="small">Montant Total</label>
                                            <input type="number" class="form-control" id="montant_total" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="small">Montant Décaissé *</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('montant_decaisse') is-invalid @enderror"
                                                id="montant_decaisse" name="montant_decaisse"
                                                value="{{ old('montant_decaisse') }}" required>
                                            @error('montant_decaisse')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="small">Reste</label>
                                            <input type="number" class="form-control" id="reste" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="banque_id" class="small">Banque</label>
                                        <select class="form-select" name="banque_id" id="banque_id">
                                            <option value="">-- Sélectionner une banque --</option>
                                            @foreach($banques as $banque)
                                                <option value="{{ $banque->id }}">{{ $banque->designation }} - {{ $banque->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="caisse_id" class="small">Caisse</label>
                                        <select class="form-select" name="caisse_id" id="caisse_id">
                                            <option value="">-- Sélectionner une caisse --</option>
                                            @foreach($caisses as $caisse)
                                                <option value="{{ $caisse->id }}">{{ $caisse->designation }} - {{ $caisse->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="reference" class="small">Référence</label>
                                        <input type="text" class="form-control" id="reference" name="reference" value="{{ old('reference') }}" placeholder="Référence du paiement">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Enregistrer
                                    </button>
                                    <a href="{{ route('gestions_decaissements.index') }}" class="btn btn-secondary">
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialiser Select2
            $('#achat_select').select2({
                ajax: {
                    url: '{{ route('decaissements.searchAchats') }}',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    }
                },
                minimumInputLength: 0,
                placeholder: 'Rechercher un achat...'
            });

            // Charger les détails quand on change l'achat
            $('#achat_select').on('change', function() {
                const achatId = $(this).val();
                if (achatId) {
                    $.ajax({
                        url: '/decaissements/achat-details/' + achatId,
                        type: 'GET',
                        success: function(data) {
                            $('#fournisseur_nom').val(data.fournisseur_nom);
                            $('#montant_total').val(data.montant_total);
                            calculateReste();
                        }
                    });
                } else {
                    $('#fournisseur_nom').val('');
                    $('#montant_total').val('');
                    $('#reste').val('');
                }
            });

            // Calculer le reste en temps réel
            $('#montant_decaisse').on('input', function() {
                calculateReste();
            });

            function calculateReste() {
                const montantTotal = parseFloat($('#montant_total').val()) || 0;
                const montantDecaisse = parseFloat($('#montant_decaisse').val()) || 0;
                const reste = montantTotal - montantDecaisse;
                $('#reste').val(reste.toFixed(2));
            }
        });
    </script>
@endsection
