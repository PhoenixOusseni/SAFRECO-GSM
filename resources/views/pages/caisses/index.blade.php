@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Caisses</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Caisses</li>
                    </ol>
                </nav>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCaisseModal">
                <i class="bi bi-plus-circle"></i>&nbsp; Ajouter une Caisse
            </button>
        </div>
    </div><!-- End Page Title -->

    <!-- Messages d'alerte -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Liste des Caisses</h5>

                        @if (count($caisses) == 0)
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> Aucune caisse enregistrée.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Designation</th>
                                            <th>Téléphone</th>
                                            <th>Email</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($caisses as $index => $caisse)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $caisse->code }}</span>
                                                </td>
                                                <td>{{ $caisse->designation }}</td>
                                                <td>{{ $caisse->telephone ?? '-' }}</td>
                                                <td>{{ $caisse->email ?? '-' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestions_caisses.show', $caisse->id) }}"
                                                        class="btn btn-sm btn-success" title="Voir">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('gestions_caisses.edit', $caisse->id) }}"
                                                        class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="{{ route('gestions_caisses.destroy', $caisse->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Êtes-vous sûr ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Supprimer">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
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

    <!-- Modal Ajouter Caisse -->
    <div class="modal fade" id="addCaisseModal" tabindex="-1" aria-labelledby="addCaisseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCaisseModalLabel">
                        <i class="bi bi-plus-circle"></i> Ajouter une Caisse
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gestions_caisses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="designation" class="form-label small">Dénomination <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="designation" name="designation"
                                    placeholder="Dénomination de la banque" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label small">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone"
                                    placeholder="Numéro de téléphone">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label small">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Adresse email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="adresse" class="form-label small">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse"
                                    placeholder="Adresse complète">
                            </div>
                        </div>
                    </div>
                    <div class="mx-3 mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
