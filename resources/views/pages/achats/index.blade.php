@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Achats</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Achats</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('gestions_achats.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Nouvel Achat
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
                        <h5 class="card-title">Liste des Achats</h5>

                        <!-- Filtres -->
                        <form method="GET" action="{{ route('gestions_achats.index') }}" class="mb-3">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <input type="text" name="numero_achat" class="form-control" placeholder="N° Achat"
                                        value="{{ request('numero_achat') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="fournisseur_id" class="form-control">
                                        <option value="">Tous les fournisseurs</option>
                                        @foreach ($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}"
                                                {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                                {{ $fournisseur->nom }}
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
                                        <th>N° Achat</th>
                                        <th>Date</th>
                                        <th>Designation</th>
                                        <th>Fournisseur</th>
                                        <th class="text-end">Montant Total</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($achats as $index => $achat)
                                        <tr>
                                            <td>{{ $achats->firstItem() + $index }}</td>
                                            <td><span class="badge bg-primary">{{ $achat->numero_achat }}</span></td>
                                            <td>{{ $achat->date_achat->format('d/m/Y') }}</td>
                                            <td>{{ $achat->designation }}</td>
                                            <td>{{ $achat->fournisseur->nom }}</td>
                                            <td class="text-end fw-bold text-success">
                                                {{ number_format($achat->montant_total, 0, ',', ' ') }}
                                            </td>
                                            <td>
                                                @if ($achat->statut === 'brouillon')
                                                    <span class="badge bg-primary"><i class="bi bi-check-circle"></i>
                                                        brouillon</span>
                                                @elseif($achat->statut === 'valide')
                                                    <span class="badge bg-success"><i class="bi bi-hourglass-split"></i>
                                                        valide</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i>
                                                        annulé</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('gestions_achats.show', $achat->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Aucun achat enregistré
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $achats->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
