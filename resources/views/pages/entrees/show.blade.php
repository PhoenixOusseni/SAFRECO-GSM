@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Détails de l'Entrée {{ $entree->code }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_entrees.index') }}">Entrées</a></li>
                        <li class="breadcrumb-item active">{{ $entree->numero_entree }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_entrees.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_entrees.edit', $entree->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="{{ route('entrees.print', $entree->id) }}" class="btn btn-success" target="_blank">
                    <i class="bi bi-printer"></i>
                </a>
                <form action="{{ route('gestions_entrees.destroy', $entree->id) }}" method="POST"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée ?');">
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
            <!-- Informations G�n�rales -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations de l'Entrée</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Numéro d'Entrée:</td>
                                            <td>
                                                <span class="badge bg-primary fs-6">{{ $entree->numero_entree }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date d'Entrée:</td>
                                            <td>{{ $entree->date_entree->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Fournisseur:</td>
                                            <td>
                                                <strong>{{ $entree->fournisseur->raison_sociale ?? $entree->fournisseur->nom }}</strong>
                                                @if ($entree->fournisseur->telephone)
                                                    <br><small class="text-muted">
                                                        <i class="bi bi-telephone"></i>
                                                        {{ $entree->fournisseur->telephone }}
                                                    </small>
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
                                            <td class="fw-bold" style="width: 40%;">Numéro de Facture:</td>
                                            <td>{{ $entree->numero_facture ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Montant Total:</td>
                                            <td>
                                                <span class="fs-5 fw-bold text-success">
                                                    {{ number_format($entree->montant_total, 0, ',', ' ') }} FCFA
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Statut:</td>
                                            <td>
                                                @if ($entree->statut === 'recu')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Reçu
                                                    </span>
                                                @elseif($entree->statut === 'en_attente')
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-hourglass-split"></i> En Attente
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle"></i> Rejeté
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($entree->observations)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong><i class="bi bi-info-circle"></i> Observations:</strong><br>
                                        {{ $entree->observations }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- D�tails des Articles -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Détails des Articles</h5>

                        @if ($entree->details->isEmpty())
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> Aucun article enregistré pour cette entrée.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Article</th>
                                            <th>Dépôt</th>
                                            <th class="text-center">Quantité</th>
                                            <th class="text-end">Prix Unitaire</th>
                                            <th class="text-end" colspan="2">Prix Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($entree->details as $index => $detail)
                                            <tr>
                                                <td>{{ $detail->article->code }}</td>
                                                <td>
                                                    <strong>{{ $detail->article->designation }}</strong>
                                                </td>
                                                <td>
                                                    <i class="bi bi-building"></i> {{ $detail->depot->designation }}
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-info">{{ number_format($detail->stock, 0, ',', ' ') }}</span>
                                                </td>
                                                <td class="text-end" colspan="2">
                                                    {{ number_format($detail->prix_achat, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td class="text-end">
                                                    <strong>{{ number_format($detail->prix_total, 0, ',', ' ') }}
                                                        FCFA</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="6" class="text-end fw-bold">TOTAL GÉNÉRAL:</td>
                                            <td class="text-end">
                                                <strong class="text-success fs-5">
                                                    {{ number_format($entree->montant_total, 0, ',', ' ') }} FCFA
                                                </strong>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Récapitulatif par Dépôt -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Récapitulatif par Dépôt</h5>

                        @php
                            $depotStats = $entree->details->groupBy('depot_id')->map(function ($details, $depotId) {
                                return [
                                    'depot' => $details->first()->depot,
                                    'nombre_articles' => $details->count(),
                                    'quantite_totale' => $details->sum('stock'),
                                    'montant_total' => $details->sum('prix_total'),
                                ];
                            });
                        @endphp

                        <div class="row">
                            @foreach ($depotStats as $stat)
                                <div class="col-md-4">
                                    <div class="card border-primary mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <i class="bi bi-building"></i> {{ $stat['depot']->designation }}
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2">
                                                    <i class="bi bi-box-seam text-primary"></i>
                                                    <strong>Nombre articles:</strong> {{ $stat['nombre_articles'] }}
                                                </li>
                                                <li class="mb-2">
                                                    <i class="bi bi-calculator text-success"></i>
                                                    <strong>Quantité totale:</strong>
                                                    {{ number_format($stat['quantite_totale'], 0, ',', ' ') }}
                                                </li>
                                                <li>
                                                    <i class="bi bi-currency-dollar text-warning"></i>
                                                    <strong>Montant:</strong>
                                                    {{ number_format($stat['montant_total'], 0, ',', ' ') }} FCFA
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Actions</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('gestions_entrees.edit', $entree->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-info" onclick="window.print()">
                                <i class="bi bi-printer"></i>
                            </button>
                            <form action="{{ route('gestions_entrees.destroy', $entree->id) }}" method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('styles')
    <style>
        @media print {

            .pagetitle .btn,
            .card-body h5.card-title,
            .card:last-child {
                display: none;
            }
        }
    </style>
@endsection
