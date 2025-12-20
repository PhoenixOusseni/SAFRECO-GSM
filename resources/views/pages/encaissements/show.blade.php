@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Details de l'Encaissement</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_encaissements.index') }}">Encaissements</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_encaissements.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_encaissements.edit', $encaissement->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="#" class="btn btn-success" onclick="window.open('{{ route('encaissements.print', $encaissement->id) }}', '_blank')">
                    <i class="bi bi-printer"></i>
                </a>
                @php
                    $reste = $encaissement->vente->montant_total - $encaissement->montant_encaisse;
                @endphp
                @if($reste > 0)
                    <a href="{{ route('gestions_encaissements.settle', $encaissement->id) }}" class="btn btn-info">
                        <i class="bi bi-cash-coin"></i> Solder
                    </a>
                @endif
                <form action="{{ route('gestions_encaissements.destroy', $encaissement->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('etes-vous sûr de vouloir supprimer cet encaissement ?');">
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Informations de l'Encaissement -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations de l'Encaissement</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Date d'Encaissement:</td>
                                            <td>
                                                <span class="badge bg-primary fs-6">{{ $encaissement->date_encaissement->format('d/m/Y') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Mode de Paiement:</td>
                                            <td>
                                                @if($encaissement->mode_paiement)
                                                    <span class="badge bg-info">{{ $encaissement->mode_paiement }}</span>
                                                @else
                                                    <span class="text-muted">Non spécifié</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Statut:</td>
                                            <td>
                                                @if($encaissement->reste == 0)
                                                    <span class="badge bg-success fs-6">
                                                        <i class="bi bi-check-circle"></i> Soldé
                                                    </span>
                                                @elseif($encaissement->reste > 0)
                                                    <span class="badge bg-warning text-dark fs-6">
                                                        <i class="bi bi-exclamation-triangle"></i> Partiel
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger fs-6">
                                                        <i class="bi bi-x-circle"></i> Trop-perçu
                                                    </span>
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
                                                    {{ number_format($encaissement->montant, 0, ',', ' ') }} FCFA
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Montant Encaissé:</td>
                                            <td>
                                                <span class="fs-5 fw-bold text-success">
                                                    {{ number_format($encaissement->montant_encaisse, 0, ',', ' ') }} FCFA
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Reste à Payer:</td>
                                            <td>
                                                <span class="fs-5 fw-bold {{ $encaissement->reste > 0 ? 'text-warning' : ($encaissement->reste == 0 ? 'text-success' : 'text-danger') }}">
                                                    {{ number_format($encaissement->reste, 0, ',', ' ') }} FCFA
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

            <!-- Informations de la Vente Associée -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-receipt"></i> Vente Associée
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Numéro de Vente:</td>
                                            <td>
                                                <a href="{{ route('gestions_ventes.show', $encaissement->vente_id) }}" class="text-decoration-none">
                                                    <span class="badge bg-secondary fs-6">{{ $encaissement->vente->numero_vente }}</span>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date de Vente:</td>
                                            <td>{{ $encaissement->vente->date_vente->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Montant Total:</td>
                                            <td>
                                                <span class="fw-bold">{{ number_format($encaissement->vente->montant_total, 0, ',', ' ') }} FCFA</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Client:</td>
                                            <td>
                                                <strong>{{ $encaissement->vente->client->raison_sociale ?? $encaissement->vente->client->nom }}</strong>
                                                @if ($encaissement->vente->client->telephone)
                                                    <br><small class="text-muted">
                                                        <i class="bi bi-telephone"></i> {{ $encaissement->vente->client->telephone }}
                                                    </small>
                                                @endif
                                                @if ($encaissement->vente->client->email)
                                                    <br><small class="text-muted">
                                                        <i class="bi bi-envelope"></i> {{ $encaissement->vente->client->email }}
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Numéro Véhicule:</td>
                                            <td>{{ $encaissement->vente->numero_vehicule ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Chauffeur:</td>
                                            <td>{{ $encaissement->vente->chauffeur ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des Soldes Effectués -->
            @if($encaissement->soldes()->count() > 0)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-list-check"></i> Historique des Soldes Effectués
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Date du Solde</th>
                                            <th>Montant Soldé</th>
                                            <th>Mode de Paiement</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($encaissement->soldes()->orderBy('date_solde', 'desc')->get() as $index => $solde)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $solde->date_solde->format('d/m/Y') }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-success">{{ number_format($solde->montant, 0, ',', ' ') }} FCFA</span>
                                                </td>
                                                <td>
                                                    @if($solde->mode_paiement)
                                                        <span class="badge bg-info">{{ $solde->mode_paiement }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($solde->notes)
                                                        <small class="text-muted">{{ Str::limit($solde->notes, 50) }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="2" class="text-end">Total Soldé:</th>
                                            <th class="text-end text-success">
                                                {{ number_format($encaissement->soldes()->sum('montant'), 0, ',', ' ') }} FCFA
                                            </th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Informations de Traçabilité -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations de Traçabilité</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Créé le:</td>
                                            <td>{{ $encaissement->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Dernière modification:</td>
                                            <td>{{ $encaissement->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
