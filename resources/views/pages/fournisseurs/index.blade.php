@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Fournisseurs</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Gestion des Fournisseurs</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_fournisseurs.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>&nbsp; Ajouter un Fournisseur
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload"></i>&nbsp; Importer CSV
                </button>
                <a href="{{ route('fournisseurs.template') }}" class="btn btn-secondary">
                    <i class="bi bi-download"></i>&nbsp; Télécharger Template
                </a>
            </div>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                @if(session('errors_detail'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erreurs d'importation détectées:</h5>
                        <ul class="mb-0">
                            @foreach(session('errors_detail') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Liste des Fournisseurs</h5>
                        <table class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom / Raison Sociale</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Téléphone</th>
                                    <th scope="col">Adresse</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fournisseurs as $fournisseur)
                                    <tr>
                                        <th scope="row">{{ $fournisseur->code }}</th>
                                        <td>{{ $fournisseur->raison_sociale ?? $fournisseur->nom }}</td>
                                        <td>{{ $fournisseur->email }}</td>
                                        <td>{{ $fournisseur->telephone }}</td>
                                        <td>{{ $fournisseur->adresse }}</td>
                                        <td>
                                            <a href="{{ route('gestions_fournisseurs.show', $fournisseur->id) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('gestions_fournisseurs.edit', $fournisseur->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Import CSV -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Importer des Fournisseurs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('fournisseurs.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Fichier CSV</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".csv,.txt" required>
                            <div class="form-text">
                                Format attendu: Type, Raison Sociale, Nom, Adresse, Téléphone, Email, Ville
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <strong>Note:</strong> Le fichier doit être au format CSV avec les colonnes dans l'ordre suivant:
                            <ul class="mb-0 mt-2">
                                <li>Type (entreprise ou particulier)</li>
                                <li>Raison Sociale</li>
                                <li>Nom</li>
                                <li>Adresse</li>
                                <li>Téléphone</li>
                                <li>Email</li>
                                <li>Ville</li>
                            </ul>
                            <p class="mt-2 mb-0"><strong>Le code sera généré automatiquement (FRS-00001, FRS-00002, etc.)</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
