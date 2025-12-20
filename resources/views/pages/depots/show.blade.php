@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Détails du Dépôt</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_depots.index') }}">Dépôts</a></li>
                        <li class="breadcrumb-item active">{{ $depot->designation }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_depots.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_depots.edit', $depot->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('gestions_depots.destroy', $depot->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Êtes-vous sûr ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <!-- Informations du Dépôt -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-building"></i> Informations du Dépôt</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 35%;">Code:</td>
                                            <td>
                                                <span class="badge bg-primary fs-6">{{ $depot->code }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Désignation:</td>
                                            <td><strong>{{ $depot->designation }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Localisation:</td>
                                            <td>
                                                <i class="bi bi-geo-alt"></i> {{ $depot->localisation ?? '-' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 35%;">Responsable:</td>
                                            <td>{{ $depot->responsable ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Contact:</td>
                                            <td>
                                                @if($depot->contact)
                                                    <i class="bi bi-telephone"></i> {{ $depot->contact }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Description:</td>
                                            <td>{{ $depot->description ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($depot->description)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong><i class="bi bi-info-circle"></i> Description:</strong><br>
                                        {{ $depot->description }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistiques du Dépôt -->
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Nombre d'Articles</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ count($articlesAvecDetails) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Quantité Totale</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calculator"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($articlesAvecDetails->sum(function($item) { return $item['stock']->quantite_disponible; }), 0, ',', ' ') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Quantité Réservée</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-lock"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($articlesAvecDetails->sum(function($item) { return $item['stock']->quantite_reserve; }), 0, ',', ' ') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card info-card visitors-card">
                            <div class="card-body">
                                <h5 class="card-title">Quantité Réelle</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($articlesAvecDetails->sum(function($item) { return $item['stock']->quantite_disponible - $item['stock']->quantite_reserve; }), 0, ',', ' ') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des Articles -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-list-check"></i> Articles dans le Dépôt</h5>

                        @if(count($articlesAvecDetails) == 0)
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> Aucun article dans ce dépôt.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Code article</th>
                                            <th>Désignation</th>
                                            <th>Date Numéro d'Entrée</th>
                                            <th class="text-center">Quantité Réelle</th>
                                            <th class="text-end">Prix Achat</th>
                                            <th class="text-end">Montant total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($articlesAvecDetails as $index => $item)
                                            @php
                                                $stock = $item['stock'];
                                                $lastEntree = $item['lastEntree'];
                                                $quantiteReelle = $stock->quantite_disponible - $stock->quantite_reserve;
                                                $statusBadge = $quantiteReelle > $stock->quantite_minimale ? 'success' : 'warning';
                                                $statusText = $quantiteReelle > $stock->quantite_minimale ? 'Normal' : 'Alerte';
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $stock->article->code }}</td>
                                                <td>{{ $stock->article->designation }}</td>
                                                <td>
                                                    @if($lastEntree)
                                                        {{ $lastEntree->entree->date_entree->format('d/m/Y') }}
                                                        <small class="text-muted">{{ $lastEntree->entree->numero_entree }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $statusBadge }}">
                                                        {{ number_format($quantiteReelle, 2, ',', ' ') }}
                                                        @if($statusText === 'Alerte')
                                                            <i class="bi bi-exclamation-triangle"></i>
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    @if($lastEntree)
                                                        {{ number_format($lastEntree->prix_achat, 0, ',', ' ') }} FCFA
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <strong>
                                                        {{ number_format($quantiteReelle * ($lastEntree->prix_achat ?? 0), 0, ',', ' ') }} FCFA
                                                    </strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Alertes de Stock -->
            @php
                $articlesEnAlerte = collect($articlesAvecDetails)->filter(function($item) {
                    $quantiteReelle = $item['stock']->quantite_disponible - $item['stock']->quantite_reserve;
                    return $quantiteReelle <= $item['stock']->quantite_minimale;
                });
            @endphp

            @if(count($articlesEnAlerte) > 0)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-exclamation-triangle"></i> Articles en Alerte</h5>
                            <div class="alert alert-warning" role="alert">
                                <strong>Attention!</strong> {{ count($articlesEnAlerte) }} article(s) est/sont en-dessous du seuil minimum.
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Article</th>
                                            <th class="text-center">Quantité Réelle</th>
                                            <th class="text-center">Seuil Minimum</th>
                                            <th class="text-center">Manquant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($articlesEnAlerte as $item)
                                            @php
                                                $quantiteReelle = $item['stock']->quantite_disponible - $item['stock']->quantite_reserve;
                                                $manquant = $item['stock']->quantite_minimale - $quantiteReelle;
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $item['stock']->article->designation }}</strong></td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger">{{ number_format($quantiteReelle, 2, ',', ' ') }}</span>
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($item['stock']->quantite_minimale, 2, ',', ' ') }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger">{{ number_format($manquant, 2, ',', ' ') }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@section('styles')
    <style>
        .info-card {
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border-radius: 50%;
        }

        .sales-card .card-icon {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .revenue-card .card-icon {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .customers-card .card-icon {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .visitors-card .card-icon {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }
    </style>
@endsection
