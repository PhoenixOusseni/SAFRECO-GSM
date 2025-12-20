@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Détails de la Vente {{ $vente->numero_vente }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_ventes.index') }}">Ventes</a></li>
                        <li class="breadcrumb-item active">{{ $vente->numero_vente }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_ventes.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_ventes.edit', $vente->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="#" class="btn btn-success" onclick="window.open('{{ route('ventes.print', $vente->id) }}', '_blank')">
                    <i class="bi bi-printer"></i>
                </a>
                <form action="{{ route('gestions_ventes.destroy', $vente->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('tes-vous sûr de vouloir supprimer cette vente ?');">
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

        <div class="row">
            <!-- Informations Générales -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations de la Vente <span class="badge bg-primary text-white fs-6">{{ $vente->statut }}</span></h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Numéro Vente:</td>
                                            <td>
                                                <span class="badge bg-primary fs-6">{{ $vente->numero_vente }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date Vente:</td>
                                            <td>{{ $vente->date_vente->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Client:</td>
                                            <td>
                                                {{ $vente->client->raison_sociale ?? $vente->client->nom }}
                                                @if ($vente->client->telephone)
                                                    <br><small class="text-muted">
                                                        <i class="bi bi-telephone"></i> {{ $vente->client->telephone }}
                                                    </small>
                                                @endif
                                                @if ($vente->client->email)
                                                    <br><small class="text-muted">
                                                        <i class="bi bi-envelope"></i> {{ $vente->client->email }}
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
                                            <td class="fw-bold">Montant Total:</td>
                                            <td>
                                                <span class="fs-5 fw-bold text-success">
                                                    {{ number_format($vente->montant_total, 2, ',', ' ') }} FCFA
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Numéro Véhicule:</td>
                                            <td>{{ $vente->numero_vehicule ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Chauffeur:</td>
                                            <td>{{ $vente->chauffeur ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- D�tails des Articles -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Détails des Articles</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Code article</th>
                                        <th>Designation</th>
                                        <th>Dépôt</th>
                                        <th class="text-end">Quantité</th>
                                        <th class="text-end">Prix Unitaire</th>
                                        <th class="text-end">Prix Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vente->details as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->article->code }}</td>
                                            <td>{{ $detail->article->designation }}</td>
                                            <td>{{ $detail->depot->designation }}</td>
                                            <td class="text-end">{{ number_format($detail->quantite, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($detail->prix_vente, 0, ',', ' ') }}</td>
                                            <td class="text-end">
                                                <strong>{{ number_format($detail->prix_total, 0, ',', ' ') }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="5" class="text-end">Total Général</th>
                                        <th class="text-end" colspan="2">
                                            <span class="fs-5 text-success">
                                                {{ number_format($vente->montant_total, 2, ',', ' ') }} FCFA
                                            </span>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de Traçabilité -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations de Traçabilité</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Créé le:</td>
                                            <td>{{ $vente->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Dernière modification:</td>
                                            <td>{{ $vente->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
