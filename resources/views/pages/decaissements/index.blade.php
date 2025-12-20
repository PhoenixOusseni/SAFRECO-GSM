@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Décaissements</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Décaissements</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('gestions_decaissements.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Nouveau Décaissement
                </a>
                <a href="{{ route('gestions_decaissements.unsettled') }}" class="btn btn-warning">
                    <i class="bi bi-exclamation-circle"></i> À Solder
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
                        <h5 class="card-title">Liste des Décaissements</h5>

                        <!-- Filtres -->
                        <form method="GET" action="{{ route('gestions_decaissements.index') }}" class="mb-3">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <select name="achat_id" class="form-control">
                                        <option value="">Tous les achats</option>
                                        @foreach ($achats as $achat)
                                            <option value="{{ $achat->id }}"
                                                {{ request('achat_id') == $achat->id ? 'selected' : '' }}>
                                                {{ $achat->numero_achat }} - {{ $achat->fournisseur->nom ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_debut" class="form-control"
                                        value="{{ request('date_debut') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_fin" class="form-control"
                                        value="{{ request('date_fin') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i> Filtrer
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date Décaissement</th>
                                        <th>N° Achat</th>
                                        <th>Fournisseur</th>
                                        <th class="text-end">Montant Total</th>
                                        <th class="text-end">Montant Décaissé</th>
                                        <th class="text-end">Reste</th>
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
                                            <td class="text-end fw-bold {{ $decaissement->reste > 0 ? 'text-warning' : 'text-success' }}">
                                                {{ number_format($decaissement->reste, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td>
                                                <a href="{{ route('gestions_decaissements.show', $decaissement->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('gestions_decaissements.edit', $decaissement->id) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('gestions_decaissements.destroy', $decaissement->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce décaissement ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Aucun décaissement enregistré
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
