@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Encaissements</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Encaissements</li>
                    </ol>
                </nav>
            </div>
            <div class="mx-0">
                <a href="{{ route('gestions_encaissements.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>&nbsp; Nouvel Encaissement
            </a>
            <a href="{{ route('gestions_encaissements.unsettled') }}" class="btn btn-warning">
                <i class="bi bi-hourglass-split"></i> Encaissements à Solder
            </a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Liste des encaissements -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Liste des Encaissements</h5>

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

                        @if ($encaissements->isEmpty())
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucun encaissement enregistré pour le moment.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>N° de pièce</th>
                                            <th>Client</th>
                                            <th>Montant Total</th>
                                            <th>Montant Encaissé</th>
                                            <th>Reste</th>
                                            <th>Mode Paiement</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($encaissements as $encaissement)
                                            <tr>
                                                <td>
                                                    <strong>{{ $loop->iteration + ($encaissements->currentPage() - 1) * $encaissements->perPage() }}</strong>
                                                </td>
                                                <td>{{ $encaissement->date_encaissement->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('gestions_ventes.show', $encaissement->vente_id) }}"
                                                        class="text-decoration-none">
                                                        {{ $encaissement->vente->numero_vente }}
                                                    </a>
                                                </td>
                                                <td>{{ $encaissement->vente->client->raison_sociale ?? $encaissement->vente->client->nom }}
                                                </td>
                                                <td class="text-end">
                                                    <span
                                                        class="fw-bold">{{ number_format($encaissement->montant, 0, ',', ' ') }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <span
                                                        class="text-success fw-bold">{{ number_format($encaissement->montant_encaisse, 0, ',', ' ') }}</span>
                                                </td>
                                                <td class="text-end">
                                                    @if ($encaissement->reste > 0)
                                                        <span
                                                            class="badge bg-warning text-dark">{{ number_format($encaissement->reste, 0, ',', ' ') }}</span>
                                                    @elseif($encaissement->reste == 0)
                                                        <span class="badge bg-success">Soldé</span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger">{{ number_format($encaissement->reste, 0, ',', ' ') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($encaissement->mode_paiement)
                                                        <span
                                                            class="badge bg-info">{{ $encaissement->mode_paiement }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('gestions_encaissements.show', $encaissement) }}"
                                                            class="btn btn-sm btn-success" title="Voir">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('gestions_encaissements.edit', $encaissement) }}"
                                                            class="btn btn-sm btn-warning" title="Modifier">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Totaux:</th>
                                            <th class="text-end">
                                                {{ number_format($encaissements->sum('montant'), 0, ',', ' ') }}
                                            </th>
                                            <th class="text-end text-success">
                                                {{ number_format($encaissements->sum('montant_encaisse'), 0, ',', ' ') }}
                                            </th>
                                            <th class="text-end">
                                                {{ number_format($encaissements->sum('reste'), 0, ',', ' ') }}
                                            </th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $encaissements->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
