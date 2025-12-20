@extends('layouts.master')
@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Ventes</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Ventes</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_ventes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>&nbsp; Nouvelle Vente
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Filtres -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Filtres de recherche</h5>
                        <form action="{{ route('gestions_ventes.index') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="numero_vente" class="form-label">N° Vente</label>
                                    <input type="text" class="form-control" id="numero_vente" name="numero_vente"
                                        value="{{ request('numero_vente') }}" placeholder="Rechercher...">
                                </div>
                                <div class="col-md-3">
                                    <label for="client_id" class="form-label">Client</label>
                                    <select class="form-select" id="client_id" name="client_id">
                                        <option value="">Tous les clients</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}"
                                                {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->raison_sociale ?? $client->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="date_debut" class="form-label">Date début</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut"
                                        value="{{ request('date_debut') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="date_fin" class="form-label">Date fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin"
                                        value="{{ request('date_fin') }}">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Rechercher
                                </button>
                                <a href="{{ route('gestions_ventes.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Réinitialiser
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Liste des ventes -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Liste des Ventes</h5>

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

                        @if ($ventes->isEmpty())
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucune vente enregistrée pour le moment.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>N° vente</th>
                                            <th>Client</th>
                                            <th>Véhicule</th>
                                            <th>Chauffeur</th>
                                            <th>Montant Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ventes as $vente)
                                            <tr>
                                                <td>
                                                    <strong>{{ $loop->iteration + ($ventes->currentPage() - 1) * $ventes->perPage() }}</strong>
                                                </td>
                                                <td>{{ $vente->date_vente->format('d/m/Y') }}</td>
                                                <td>{{ $vente->numero_vente }}</td>
                                                <td>{{ $vente->client->raison_sociale ?? $vente->client->nom }}</td>
                                                <td>{{ $vente->numero_vehicule ?? '-' }}</td>
                                                <td>{{ $vente->chauffeur ?? '-' }}</td>
                                                <td>{{ number_format($vente->montant_total, 0, ',', ' ') }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('gestions_ventes.show', $vente) }}"
                                                            class="btn btn-sm btn-success" title="Voir">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        @if ($vente->statut === 'brouillon')
                                                            <a href="{{ route('gestions_ventes.edit', $vente) }}"
                                                                class="btn btn-sm btn-warning" title="Modifier">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $ventes->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
