@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Détails du Fournisseur</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_fournisseurs.index') }}">Fournisseurs</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('gestions_fournisseurs.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_fournisseurs.edit', $fournisseur->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('gestions_fournisseurs.destroy', $fournisseur->id) }}" method="POST"
                    style="display:inline;"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ? Cette action est irréversible.')">
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
        <div class="row">
            <!-- Informations du fournisseur -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-building"></i> Informations du Fournisseur
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th width="200">Code Fournisseur:</th>
                                            <td><span class="badge bg-primary">{{ $fournisseur->code }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Type:</th>
                                            <td>{{ $fournisseur->type ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nom:</th>
                                            <td>{{ $fournisseur->nom }}</td>
                                        </tr>
                                        <tr>
                                            <th>Raison Sociale:</th>
                                            <td>{{ $fournisseur->raison_sociale ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ville:</th>
                                            <td>{{ $fournisseur->ville ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th width="200">Téléphone:</th>
                                            <td>
                                                @if ($fournisseur->telephone)
                                                    <i class="bi bi-telephone"></i> {{ $fournisseur->telephone }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>
                                                @if ($fournisseur->email)
                                                    <i class="bi bi-envelope"></i> {{ $fournisseur->email }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Adresse:</th>
                                            <td>
                                                @if ($fournisseur->adresse)
                                                    <i class="bi bi-geo-alt"></i> {{ $fournisseur->adresse }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Créé le:</th>
                                            <td>{{ $fournisseur->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Modifié le:</th>
                                            <td>{{ $fournisseur->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques des d�caissements -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-graph-down"></i> Statistiques des Décaissements
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-danger">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-muted">Montant Total Décaisse</h6>
                                        <h3 class="text-danger mb-0">
                                            <i class="bi bi-cash-stack"></i>
                                            {{ number_format($montantTotalDecaisse, 0, ',', ' ') }} FCFA
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-muted">Montant Total à Solder</h6>
                                        <h3 class="text-warning mb-0">
                                            <i class="bi bi-hourglass-split"></i>
                                            {{ number_format($montantTotalASolder, 0, ',', ' ') }} FCFA
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des achats et d�caissements -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-cart"></i> Historique des Achats et Décaissements
                        </h5>
                        @if ($achats->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N° Achat</th>
                                            <th>Date Achat</th>
                                            <th>Désignation</th>
                                            <th>Montant Total</th>
                                            <th>Montant Décaisse</th>
                                            <th>Reste à Solder</th>
                                            <th>Statut</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($achats as $achat)
                                            @php
                                                $totalDecaisse = $achat->decaissements->sum('montant_decaisse');
                                                $totalReste = $achat->decaissements->sum('reste');
                                            @endphp
                                            <tr>
                                                <td><span class="badge bg-primary">{{ $achat->numero_achat }}</span></td>
                                                <td>{{ $achat->date_achat->format('d/m/Y') }}</td>
                                                <td>{{ $achat->designation ?? 'N/A' }}</td>
                                                <td>{{ number_format($achat->montant_total, 0, ',', ' ') }} FCFA</td>
                                                <td class="text-danger fw-bold">
                                                    {{ number_format($totalDecaisse, 0, ',', ' ') }} FCFA</td>
                                                <td class="text-{{ $totalReste > 0 ? 'warning' : 'success' }}">
                                                    {{ number_format($totalReste, 0, ',', ' ') }} FCFA</td>
                                                <td>
                                                    @if ($achat->statut == 'validee')
                                                        <span class="badge bg-success">Validé</span>
                                                    @elseif($achat->statut == 'en_attente')
                                                        <span class="badge bg-warning">En attente</span>
                                                    @else
                                                        <span
                                                            class="badge bg-secondary">{{ $achat->statut ?? 'N/A' }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($achat->decaissements->count() > 0)
                                                        @foreach ($achat->decaissements as $decaissement)
                                                            <a href="{{ route('gestions_decaissements.show', $decaissement->id) }}"
                                                                class="btn btn-sm btn-danger" title="Voir décaissement">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Aucun</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Totaux:</th>
                                            <th class="text-danger">{{ number_format($montantTotalDecaisse, 0, ',', ' ') }}
                                                FCFA</th>
                                            <th class="text-warning">{{ number_format($montantTotalASolder, 0, ',', ' ') }}
                                                FCFA</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucun achat enregistré pour ce fournisseur.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
