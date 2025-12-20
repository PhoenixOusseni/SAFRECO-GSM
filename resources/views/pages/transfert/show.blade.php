@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Détails du Transfert</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_transferts.index') }}">Transferts</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_transferts.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_transferts.edit', $transfert->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="#" class="btn btn-success" onclick="window.open('{{ route('transferts.print', $transfert->id) }}', '_blank')">
                    <i class="bi bi-printer"></i>
                </a>
                <form action="{{ route('gestions_transferts.destroy', $transfert->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('tes-vous sûr de vouloir supprimer ce transfert ? Les stocks seront restaurés.');">
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
            <!-- Informations du Transfert -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-arrow-left-right"></i>Informations du Transfert</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 35%;">Code:</td>
                                            <td>
                                                <span class="badge bg-secondary fs-6">{{ $transfert->code }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date de Transfert:</td>
                                            <td>
                                                <span class="badge bg-primary fs-6">{{ $transfert->date_transfert->format('d/m/Y') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Article:</td>
                                            <td>
                                                <strong>{{ $transfert->article->designation }}</strong>
                                                <br><small class="text-muted">Code: {{ $transfert->article->code }}</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Quantité Transférée:</td>
                                            <td>
                                                <span class="badge bg-success fs-5">{{ number_format($transfert->quantite, 2, ',', ' ') }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 35%;">Numéro de Véhicule:</td>
                                            <td>{{ $transfert->numero_vehicule ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nom du Chauffeur:</td>
                                            <td>{{ $transfert->nom_chauffeur ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date transfert:</td>
                                            <td>{{ $transfert->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($transfert->observation)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong><i class="bi bi-info-circle"></i> Observation:</strong><br>
                                        {{ $transfert->observation }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- D�tails des D�p�ts -->
            <div class="col-lg-6">
                <div class="card border-danger">
                    <div class="card-body">
                        <h5 class="card-title text-danger">
                            <i class="bi bi-building"></i> Dépôt Source
                        </h5>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Code:</td>
                                    <td><span class="badge bg-danger">{{ $transfert->depotSource->code }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Désignation:</td>
                                    <td><strong>{{ $transfert->depotSource->designation }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Localisation:</td>
                                    <td><i class="bi bi-geo-alt"></i> {{ $transfert->depotSource->localisation ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Responsable:</td>
                                    <td>{{ $transfert->depotSource->responsable ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">
                            <i class="bi bi-building"></i> Dépôt Destination
                        </h5>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Code:</td>
                                    <td><span class="badge bg-success">{{ $transfert->depotDestination->code }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Désignation:</td>
                                    <td><strong>{{ $transfert->depotDestination->designation }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Localisation:</td>
                                    <td><i class="bi bi-geo-alt"></i> {{ $transfert->depotDestination->localisation ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Responsable:</td>
                                    <td>{{ $transfert->depotDestination->responsable ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Schéma du Transfert -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-diagram-3"></i> Schéma du Transfert</h5>
                        <div class="d-flex justify-content-center align-items-center py-4">
                            <div class="text-center">
                                <div class="p-3 border border-danger rounded bg-light" style="min-width: 200px;">
                                    <i class="bi bi-building fs-1 text-danger"></i>
                                    <h6 class="mt-2 text-danger">{{ $transfert->depotSource->designation }}</h6>
                                    <small class="text-muted">Source</small>
                                </div>
                            </div>

                            <div class="mx-4 text-center">
                                <i class="bi bi-arrow-right fs-1 text-primary"></i>
                                <div class="mt-2">
                                    <span class="badge bg-success fs-5">{{ number_format($transfert->quantite, 2, ',', ' ') }}</span>
                                    <div><small class="text-muted">{{ $transfert->article->designation }}</small></div>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="p-3 border border-success rounded bg-light" style="min-width: 200px;">
                                    <i class="bi bi-building fs-1 text-success"></i>
                                    <h6 class="mt-2 text-success">{{ $transfert->depotDestination->designation }}</h6>
                                    <small class="text-muted">Destination</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de Transport -->
            @if($transfert->numero_vehicule || $transfert->nom_chauffeur)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-truck"></i> Informations de Transport</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <i class="bi bi-car-front fs-2 text-primary me-3"></i>
                                        <div>
                                            <small class="text-muted">Véhicule</small>
                                            <h6 class="mb-0">{{ $transfert->numero_vehicule ?? 'Non renseigné' }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <i class="bi bi-person-circle fs-2 text-primary me-3"></i>
                                        <div>
                                            <small class="text-muted">Chauffeur</small>
                                            <h6 class="mb-0">{{ $transfert->nom_chauffeur ?? 'Non renseigné' }}</h6>
                                        </div>
                                    </div>
                                </div>
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
        .border-danger {
            border-width: 2px !important;
        }
        .border-success {
            border-width: 2px !important;
        }
    </style>
@endsection
