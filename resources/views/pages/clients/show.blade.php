@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Details du Client</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_clients.index') }}">Clients</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('gestions_clients.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_clients.edit', $client->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('gestions_clients.destroy', $client->id) }}" method="POST" style="display:inline;"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.')">
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
            <!-- Informations du client -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-person-badge"></i> Informations du Client
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th width="200">Code Client:</th>
                                            <td><span class="badge bg-primary">{{ $client->code }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Type:</th>
                                            <td>{{ $client->type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nom:</th>
                                            <td>{{ $client->nom }}</td>
                                        </tr>
                                        <tr>
                                            <th>Raison Sociale:</th>
                                            <td>{{ $client->raison_sociale ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ville:</th>
                                            <td>{{ $client->ville ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th width="200">éléphone:</th>
                                            <td>
                                                @if ($client->telephone)
                                                    <i class="bi bi-telephone"></i> {{ $client->telephone }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>
                                                @if ($client->email)
                                                    <i class="bi bi-envelope"></i> {{ $client->email }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Adresse:</th>
                                            <td>
                                                @if ($client->adresse)
                                                    <i class="bi bi-geo-alt"></i> {{ $client->adresse }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Créé le:</th>
                                            <td>{{ $client->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Modifié le:</th>
                                            <td>{{ $client->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques des encaissements -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-graph-up"></i> Statistiques des Encaissements
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-muted">Montant Total Encaissé</h6>
                                        <h3 class="text-success mb-0">
                                            <i class="bi bi-cash-coin"></i>
                                            {{ number_format($montantTotalEncaisse, 0, ',', ' ') }} FCFA
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

            <!-- Historique des ventes et encaissements -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-receipt"></i> Historique des Ventes et Encaissements
                        </h5>
                        @if ($ventes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N° Vente</th>
                                            <th>Date Vente</th>
                                            <th>Montant Total</th>
                                            <th>Montant Encaissé</th>
                                            <th>Reste à Solder</th>
                                            <th>Statut</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ventes as $vente)
                                            @php
                                                $totalEncaisse = $vente->encaissements->sum('montant_encaisse');
                                                $totalReste = $vente->encaissements->sum('reste');
                                            @endphp
                                            <tr>
                                                <td><span class="badge bg-primary">{{ $vente->numero_vente }}</span></td>
                                                <td>{{ $vente->date_vente->format('d/m/Y') }}</td>
                                                <td>{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</td>
                                                <td class="text-success fw-bold">
                                                    {{ number_format($totalEncaisse, 0, ',', ' ') }} FCFA</td>
                                                <td class="text-{{ $totalReste > 0 ? 'warning' : 'success' }}">
                                                    {{ number_format($totalReste, 0, ',', ' ') }} FCFA</td>
                                                <td>
                                                    @if ($vente->statut == 'validee')
                                                        <span class="badge bg-success">Validée</span>
                                                    @elseif($vente->statut == 'en_attente')
                                                        <span class="badge bg-warning">En attente</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $vente->statut }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($vente->encaissements->count() > 0)
                                                        @foreach ($vente->encaissements as $encaissement)
                                                            <a href="{{ route('gestions_encaissements.show', $encaissement->id) }}"
                                                                class="btn btn-sm btn-success" title="Voir encaissement">
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
                                            <th colspan="3" class="text-end">Totaux:</th>
                                            <th class="text-success">
                                                {{ number_format($montantTotalEncaisse, 0, ',', ' ') }} FCFA</th>
                                            <th class="text-warning">{{ number_format($montantTotalASolder, 0, ',', ' ') }}
                                                FCFA</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucune vente enregistrée pour ce client.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
