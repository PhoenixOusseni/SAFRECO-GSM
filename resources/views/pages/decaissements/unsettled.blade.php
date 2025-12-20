@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Décaissements à Solder</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_decaissements.index') }}">Décaissements</a></li>
                        <li class="breadcrumb-item active">À Solder</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('gestions_decaissements.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Liste des Décaissements à Solder</h5>
                        <p class="text-muted">Décaissements avec un reste à payer supérieur à zéro</p>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>N° Achat</th>
                                        <th>Fournisseur</th>
                                        <th class="text-end">Montant Total</th>
                                        <th class="text-end">Montant Décaissé</th>
                                        <th class="text-end">Reste</th>
                                        <th>Pourcentage</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($decaissements as $index => $decaissement)
                                        <tr>
                                            <td>{{ $decaissements->firstItem() + $index }}</td>
                                            <td>{{ $decaissement->date_decaissement->format('d/m/Y') }}</td>
                                            <td><span class="badge bg-primary">{{ $decaissement->achat->numero_achat }}</span></td>
                                            <td>{{ $decaissement->achat->fournisseur->nom ?? 'N/A' }}</td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($decaissement->montant, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                {{ number_format($decaissement->montant_decaisse, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="text-end fw-bold text-warning">
                                                {{ number_format($decaissement->reste, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td>
                                                @php
                                                    $percentage = ($decaissement->montant_decaisse / $decaissement->montant) * 100;
                                                @endphp
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ number_format($percentage, 0) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('gestions_decaissements.show', $decaissement->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('gestions_decaissements.settle', $decaissement->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle"></i> Solder
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox"></i> Aucun décaissement à solder
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $decaissements->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
