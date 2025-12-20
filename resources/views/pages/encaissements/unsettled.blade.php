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
                        <li class="breadcrumb-item active">Non Soldés</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_encaissements.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-hourglass-split"></i> Encaissements à Solder
                        </h5>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($encaissements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-hashtag"></i> N° Vente</th>
                                            <th><i class="bi bi-person"></i> Client</th>
                                            <th><i class="bi bi-calendar"></i> Date Encaiss.</th>
                                            <th class="text-end"><i class="bi bi-cash-coin"></i> Montant Total</th>
                                            <th class="text-end"><i class="bi bi-check-circle"></i> Encaissé</th>
                                            <th class="text-end"><i class="bi bi-exclamation-circle"></i> Reste</th>
                                            <th class="text-center">% Payé</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($encaissements as $encaissement)
                                            @php
                                                $montant_total = $encaissement->vente->montant_total;
                                                $montant_encaisse = $encaissement->montant_encaisse;
                                                $reste = $montant_total - $montant_encaisse;
                                                $pourcentage = ($montant_encaisse / $montant_total) * 100;
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $encaissement->vente->numero_vente }}</strong></td>
                                                <td>{{ $encaissement->vente->client->raison_sociale ?? $encaissement->vente->client->nom }}</td>
                                                <td>{{ \Carbon\Carbon::parse($encaissement->date_encaissement)->format('d/m/Y') }}</td>
                                                <td class="text-end font-weight-bold">{{ number_format($montant_total, 2, ',', ' ') }} F</td>
                                                <td class="text-end">
                                                    <span class="badge bg-success">{{ number_format($montant_encaisse, 2, ',', ' ') }} F</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-warning text-dark">{{ number_format($reste, 2, ',', ' ') }} F</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar" role="progressbar"
                                                             style="width: {{ $pourcentage }}%;"
                                                             aria-valuenow="{{ $pourcentage }}" aria-valuemin="0" aria-valuemax="100">
                                                            {{ number_format($pourcentage, 0) }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('gestions_encaissements.show', $encaissement->id) }}"
                                                           class="btn btn-info" title="Voir détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('gestions_encaissements.settle', $encaissement->id) }}"
                                                           class="btn btn-success" title="Solder">
                                                            <i class="bi bi-cash-coin"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $encaissements->links() }}
                            </div>

                            <!-- Statistiques -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="card-title text-muted">Encaissements à Solder</h6>
                                            <h2 class="mb-0 text-primary">{{ $encaissements->total() }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="bi bi-info-circle"></i>
                                <strong>Félicitations !</strong><br>
                                Tous les encaissements sont entièrement payés.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
