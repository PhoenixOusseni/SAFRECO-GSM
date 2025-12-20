@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Transferts</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Transferts</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('gestions_transferts.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouveau Transfert
                </a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-arrow-left-right"></i> Liste des Transferts</h5>

                        @if($transferts->isEmpty())
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucun transfert enregistré pour le moment.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" style="min-width: 1500px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Article</th>
                                            <th class="text-center">Quantité</th>
                                            <th>Source</th>
                                            <th>Destination</th>
                                            <th>Véhicule</th>
                                            <th>Chauffeur</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transferts as $index => $transfert)
                                            <tr>
                                                <td>{{ $transferts->firstItem() + $index }}</td>
                                                <td>{{ $transfert->date_transfert->format('d/m/Y') }}</td>
                                                <td>
                                                    <strong>{{ $transfert->article->designation }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary">{{ number_format($transfert->quantite, 2, ',', ' ') }}</span>
                                                </td>
                                                <td>
                                                    <i class="bi bi-building text-danger"></i> {{ $transfert->depotSource->designation }}
                                                </td>
                                                <td>
                                                    <i class="bi bi-building text-success"></i> {{ $transfert->depotDestination->designation }}
                                                </td>
                                                <td>{{ $transfert->numero_vehicule ?? '-' }}</td>
                                                <td>{{ $transfert->nom_chauffeur ?? '-' }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('gestions_transferts.show', $transfert->id) }}"
                                                           class="btn btn-sm btn-success"
                                                           title="Voir">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('gestions_transferts.edit', $transfert->id) }}"
                                                           class="btn btn-sm btn-warning"
                                                           title="Modifier">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $transferts->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
