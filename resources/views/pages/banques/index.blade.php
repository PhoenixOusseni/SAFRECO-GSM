@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Banques</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Banques</li>
                    </ol>
                </nav>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBanqueModal">
                <i class="bi bi-plus-circle"></i>&nbsp; Ajouter une Banque
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
                        <h5 class="card-title">Liste des Banques</h5>

                        @if (count($banques) == 0)
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> Aucune banque enregistrée.
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
                                        @foreach ($banques as $index => $banque)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $banque->code }}</span>
                                                </td>
                                                <td>{{ $banque->designation }}</td>
                                                <td>
                                                    @if ($banque->telephone)
                                                        <i class="bi bi-telephone"></i> {{ $banque->telephone }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($banque->email)
                                                        <i class="bi bi-envelope"></i> {{ $banque->email }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('gestions_banques.show', $banque->id) }}"
                                                        class="btn btn-sm btn-success" title="Voir">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('gestions_banques.edit', $banque->id) }}"
                                                        class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="{{ route('gestions_banques.destroy', $banque->id) }}"
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

    <!-- Modal Ajouter Banque -->
    <div class="modal fade" id="addBanqueModal" tabindex="-1" aria-labelledby="addBanqueModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBanqueModalLabel">
                        <i class="bi bi-plus-circle"></i> Ajouter une Banque
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gestions_banques.store') }}" method="POST">
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
