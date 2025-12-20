@extends('layouts.master')
@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Entrées de Stock</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Entrées de Stock</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_entrees.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>&nbsp; Nouvelle Entrée
            </a>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Liste des Entrées</h5>

                        @if ($entrees->isEmpty())
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucune entrée de stock enregistrée pour le moment.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N° Entrée</th>
                                            <th>Date</th>
                                            <th>Fournisseur</th>
                                            <th>N° Facture</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($entrees as $entree)
                                            <tr>
                                                <td>
                                                    <strong>{{ $entree->numero_entree }}</strong>
                                                </td>
                                                <td>{{ $entree->date_entree->format('d/m/Y') }}</td>
                                                <td>{{ $entree->fournisseur->raison_sociale ?? $entree->fournisseur->nom }}
                                                </td>
                                                <td>{{ $entree->numero_facture ?? '-' }}</td>
                                                <td>{{ number_format($entree->montant_total, 0, ',', ' ') }}</td>
                                                <td>
                                                    @if ($entree->statut === 'recu')
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i>
                                                            Reçu</span>
                                                    @elseif($entree->statut === 'en_attente')
                                                        <span class="badge bg-warning"><i class="bi bi-hourglass-split"></i>
                                                            En Attente</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i>
                                                            Rejeté</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('gestions_entrees.show', $entree->id) }}"
                                                            class="btn btn-sm btn-success">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('gestions_entrees.edit', $entree->id) }}"
                                                            class="btn btn-sm btn-warning">
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
