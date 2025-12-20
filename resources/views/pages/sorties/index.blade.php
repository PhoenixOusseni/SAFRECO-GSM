@extends('layouts.master')
@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Sorties de Stock</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Sorties de Stock</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_sorties.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>&nbsp; Nouvelle Sortie
            </a>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Liste des Sorties</h5>

                        @if($sorties->isEmpty())
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucune sortie de stock enregistrée pour le moment.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N° Sortie</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Client</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sorties as $sortie)
                                            <tr>
                                                <td>
                                                    <strong>{{ $sortie->numero_sortie }}</strong>
                                                </td>
                                                <td>{{ $sortie->date_sortie->format('d/m/Y') }}</td>
                                                <td>
                                                    @switch($sortie->type_sortie)
                                                        @case('vente')
                                                            <span class="badge bg-info"><i class="bi bi-bag-check"></i> Vente</span>
                                                            @break
                                                        @case('transfert')
                                                            <span class="badge bg-secondary"><i class="bi bi-arrow-left-right"></i> Transfert</span>
                                                            @break
                                                        @case('destruction')
                                                            <span class="badge bg-danger"><i class="bi bi-trash"></i> Destruction</span>
                                                            @break
                                                        @case('inventaire')
                                                            <span class="badge bg-primary"><i class="bi bi-clipboard-check"></i> Inventaire</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $sortie->client?->nom ?? $sortie->client?->raison_sociale ?? '-' }}</td>
                                                <td>
                                                    <strong>{{ number_format($sortie->montant_total, 2, ',', ' ') }} XAF</strong>
                                                </td>
                                                <td>
                                                    @if($sortie->statut === 'validee')
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Validée</span>
                                                    @elseif($sortie->statut === 'en_attente')
                                                        <span class="badge bg-warning"><i class="bi bi-hourglass-split"></i> En Attente</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rejetée</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('gestions_sorties.show', $sortie) }}" class="btn btn-sm btn-success" title="Voir">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('gestions_sorties.edit', $sortie) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    </div>
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
        </div>
    </section>
@endsection
