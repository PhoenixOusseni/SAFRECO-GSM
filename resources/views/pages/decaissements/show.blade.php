@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Détails du Décaissement</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_decaissements.index') }}">Décaissements</a>
                        </li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_decaissements.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_decaissements.edit', $decaissement->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="{{ route('decaissements.print', $decaissement->id) }}" class="btn btn-success" target="_blank">
                    <i class="bi bi-printer"></i>
                </a>
                <form action="{{ route('gestions_decaissements.destroy', $decaissement->id) }}" method="POST"
                    class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce décaissement ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <section class="section">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Informations Générales -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations du Décaissement</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Date Décaissement:</td>
                                            <td>
                                                <span
                                                    class="badge bg-primary fs-6">{{ $decaissement->date_decaissement->format('d/m/Y') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">N° Achat:</td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary fs-6">{{ $decaissement->achat->numero_achat }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Mode Paiement:</td>
                                            <td>
                                                @if ($decaissement->mode_paiement)
                                                    <span class="badge bg-info">{{ $decaissement->mode_paiement }}</span>
                                                @else
                                                    <span class="text-muted">Non spécifié</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Montant Total:</td>
                                            <td>
                                                <span class="fs-5 fw-bold">
                                                    {{ number_format($decaissement->montant, 0, ',', ' ') }} FCFA
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Montant Décaissé:</td>
                                            <td>
                                                <span class="fs-5 fw-bold text-success">
                                                    {{ number_format($decaissement->montant_decaisse, 0, ',', ' ') }} FCFA
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Reste:</td>
                                            <td>
                                                <span
                                                    class="fs-5 fw-bold {{ $decaissement->reste > 0 ? 'text-warning' : 'text-success' }}">
                                                    {{ number_format($decaissement->reste, 0, ',', ' ') }} FCFA
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de l'Achat Associé -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-receipt"></i> Achat Associé
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Numéro Achat:</td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary fs-6">{{ $decaissement->achat->numero_achat }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date Achat:</td>
                                            <td>{{ $decaissement->achat->date_achat->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Montant Total:</td>
                                            <td>
                                                <span
                                                    class="fw-bold">{{ number_format($decaissement->achat->montant_total, 0, ',', ' ') }}
                                                    FCFA</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Fournisseur:</td>
                                            <td>
                                                <strong>{{ $decaissement->achat->fournisseur->nom ?? 'N/A' }}</strong>
                                                @if ($decaissement->achat->fournisseur && $decaissement->achat->fournisseur->telephone)
                                                    <br><small class="text-muted">
                                                        <i class="bi bi-telephone"></i>
                                                        {{ $decaissement->achat->fournisseur->telephone }}
                                                    </small>
                                                @endif
                                                @if ($decaissement->achat->fournisseur && $decaissement->achat->fournisseur->email)
                                                    <br><small class="text-muted">
                                                        <i class="bi bi-envelope"></i>
                                                        {{ $decaissement->achat->fournisseur->email }}
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Statut:</td>
                                            <td>
                                                <span
                                                    class="badge bg-success">{{ $decaissement->achat->statut ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des Soldes -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-clock-history"></i> Historique des Soldes
                        </h5>

                        @if ($decaissement->soldes && count($decaissement->soldes) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Date Solde</th>
                                            <th class="text-end">Montant Solde</th>
                                            <th class="text-end">Solde Cumulé</th>
                                            <th>Mode Paiement</th>
                                            <th>Observation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($decaissement->soldes as $index => $solde)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $solde->date_solde->format('d/m/Y') }}</span>
                                                </td>
                                                <td class="text-end fw-bold text-success">
                                                    {{ number_format($solde->montant_solde, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td class="text-end fw-bold">
                                                    {{ number_format($solde->solde_cumule, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td>
                                                    @if ($solde->mode_paiement)
                                                        <span class="badge bg-info">{{ $solde->mode_paiement }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($solde->observation)
                                                        <small class="text-muted">{{ $solde->observation }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info mt-3">
                                <strong>Total Soldes:</strong> {{ count($decaissement->soldes) }} paiement(s) effectué(s)
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-info-circle"></i> Aucun solde enregistré pour ce décaissement.
                                @if ($decaissement->reste > 0)
                                    <a href="{{ route('gestions_decaissements.settle', $decaissement->id) }}"
                                        class="btn btn-sm btn-primary ms-2">
                                        <i class="bi bi-check-circle"></i> Solder maintenant
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
