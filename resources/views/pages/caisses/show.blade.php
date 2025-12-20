@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Details de la Caisse</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_caisses.index') }}">Caisses</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('gestions_caisses.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_caisses.edit', $caisse->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="#" class="btn btn-success">
                    <i class="bi bi-printer"></i>
                </a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Informations Principales -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-cash-coin"></i> Informations de la Caisse
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="fw-bold text-muted small">Code Caisse</label>
                                    <p class="mb-0">
                                        <span class="badge bg-primary fs-6">{{ $caisse->code }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="fw-bold text-muted small">Numéro de Compte</label>
                                    <p class="mb-0">
                                        <span class="badge bg-info fs-6">{{ $caisse->numero_compte }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="info-group">
                                    <label class="fw-bold text-muted small">Dénomination</label>
                                    <p class="fs-5 mb-0">{{ $caisse->designation }}</p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-geo-alt"></i> Coordonnées
                        </h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="fw-bold text-muted small">
                                        <i class="bi bi-telephone"></i> Téléphone
                                    </label>
                                    <p class="mb-0">
                                        @if ($caisse->telephone)
                                            <a href="tel:{{ $caisse->telephone }}" class="text-decoration-none">
                                                {{ $caisse->telephone }}
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="fw-bold text-muted small">
                                        <i class="bi bi-envelope"></i> Email
                                    </label>
                                    <p class="mb-0">
                                        @if ($caisse->email)
                                            <a href="mailto:{{ $caisse->email }}" class="text-decoration-none">
                                                {{ $caisse->email }}
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Non renseigné</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="fw-bold text-muted small">
                                        <i class="bi bi-pin-map"></i> Adresse
                                    </label>
                                    <p class="mb-0">
                                        @if ($caisse->adresse)
                                            {{ $caisse->adresse }}
                                        @else
                                            <span class="text-muted fst-italic">Non renseignée</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="fw-bold text-muted small">
                                        <i class="bi bi-building"></i> Ville
                                    </label>
                                    <p class="mb-0">
                                        @if ($caisse->ville)
                                            {{ $caisse->ville }}
                                        @else
                                            <span class="text-muted fst-italic">Non renseignée</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-clock-history"></i> Informations Système
                        </h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="fw-bold text-muted small">Date de Création</label>
                                    <p class="mb-0">
                                        <i class="bi bi-calendar-plus"></i>
                                        {{ $caisse->created_at ? $caisse->created_at->format('d/m/Y à H:i') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label class="fw-bold text-muted small">Dernière Modification</label>
                                    <p class="mb-0">
                                        <i class="bi bi-calendar-check"></i>
                                        {{ $caisse->updated_at ? $caisse->updated_at->format('d/m/Y à H:i') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Solde et Statistiques -->
            <div class="col-lg-4">
                <!-- Solde Actuel -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-cash-stack"></i> Solde Actuel
                        </h5>
                        <div class="text-center py-4">
                            <h2 class="text-{{ $caisse->solde >= 0 ? 'success' : 'danger' }} mb-1">
                                {{ number_format($caisse->solde, 0, ',', ' ') }} FCFA
                            </h2>
                            @if ($caisse->solde >= 0)
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-check-circle-fill text-success"></i> Solde positif
                                </p>
                            @else
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-exclamation-triangle-fill text-danger"></i> Solde négatif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-lightning-charge"></i> Actions Rapides
                        </h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('gestions_encaissements.create') }}" class="btn btn-success">
                                <i class="bi bi-arrow-down-circle"></i> Nouvel Encaissement
                            </a>
                            <a href="{{ route('gestions_decaissements.create') }}" class="btn btn-danger">
                                <i class="bi bi-arrow-up-circle"></i> Nouveau Décaissement
                            </a>
                            <a href="{{ route('gestions_caisses.edit', $caisse->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Modifier la Caisse
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Informations Compl�mentaires -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-info-circle"></i> Informations
                        </h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted small">Statut</span>
                                <span class="badge bg-success">Active</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted small">Type</span>
                                <span class="badge bg-info">Caisse</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted small">ID</span>
                                <span class="badge bg-secondary">#{{ $caisse->id }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des Encaissements -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-arrow-down-circle text-success"></i> Historique des Encaissements
                        </h5>

                        @if ($encaissements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>N° Vente</th>
                                            <th>Client</th>
                                            <th>Montant Total</th>
                                            <th>Montant Encaissé</th>
                                            <th>Reste</th>
                                            <th>Mode Paiement</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($encaissements as $encaissement)
                                            <tr>
                                                <td>{{ $encaissement->date_encaissement->format('d/m/Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-primary">{{ $encaissement->vente->numero_vente ?? 'N/A' }}</span>
                                                </td>
                                                <td>{{ $encaissement->vente->client->raison_sociale ?? ($encaissement->vente->client->nom ?? 'N/A') }}
                                                </td>
                                                <td>{{ number_format($encaissement->montant, 0, ',', ' ') }} FCFA</td>
                                                <td class="text-success fw-bold">
                                                    {{ number_format($encaissement->montant_encaisse, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td class="text-{{ $encaissement->reste > 0 ? 'warning' : 'success' }}">
                                                    {{ number_format($encaissement->reste, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info">{{ $encaissement->mode_paiement ?? 'N/A' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestions_encaissements.show', $encaissement->id) }}"
                                                        class="btn btn-sm btn-success" title="Voir">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Total Encaissé:</th>
                                            <th class="text-success">
                                                {{ number_format($encaissements->sum('montant_encaisse'), 0, ',', ' ') }}
                                                FCFA</th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucun encaissement enregistré pour cette caisse.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des Décaissements -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-arrow-up-circle text-danger"></i> Historique des Décaissements
                        </h5>

                        @if ($decaissements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>N° Achat</th>
                                            <th>Fournisseur</th>
                                            <th>Montant Total</th>
                                            <th>Montant Décaissé</th>
                                            <th>Reste</th>
                                            <th>Mode Paiement</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($decaissements as $decaissement)
                                            <tr>
                                                <td>{{ $decaissement->date_decaissement->format('d/m/Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-primary">{{ $decaissement->achat->numero_achat ?? 'N/A' }}</span>
                                                </td>
                                                <td>{{ $decaissement->achat->fournisseur->nom ?? 'N/A' }}</td>
                                                <td>{{ number_format($decaissement->montant, 0, ',', ' ') }} FCFA</td>
                                                <td class="text-danger fw-bold">
                                                    {{ number_format($decaissement->montant_decaisse, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td class="text-{{ $decaissement->reste > 0 ? 'warning' : 'success' }}">
                                                    {{ number_format($decaissement->reste, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info">{{ $decaissement->mode_paiement ?? 'N/A' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestions_decaissements.show', $decaissement->id) }}"
                                                        class="btn btn-sm btn-success" title="Voir">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Total Décaissé:</th>
                                            <th class="text-danger">
                                                {{ number_format($decaissements->sum('montant_decaisse'), 0, ',', ' ') }}
                                                FCFA</th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucun décaissement enregistré pour cette caisse.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .info-group {
            margin-bottom: 1rem;
        }

        .info-group label {
            display: block;
            margin-bottom: 0.25rem;
        }

        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-title {
            color: #012970;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            margin-bottom: 1rem;
        }
    </style>
@endsection
