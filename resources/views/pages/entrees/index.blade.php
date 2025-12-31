@extends('layouts.master')
@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Entrées de Stock</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Entrées de Stock</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_entrees.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>&nbsp; Nouvelle Entrée
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload"></i>&nbsp; Importer
                </button>
                <a href="{{ route('entrees.template') }}" class="btn btn-secondary">
                    <i class="bi bi-download"></i>&nbsp; Télécharger
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
                        <h5 class="card-title">Liste des Entrées</h5>

                        @if ($entrees->isEmpty())
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Aucune entrée de stock enregistrée pour le moment.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N° Entrée</th>
                                            <th>Date</th>
                                            <th>Fournisseur</th>
                                            <th>N° Facture</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($entrees as $entree)
                                            <tr>
                                                <td>
                                                    <strong>{{ $entree->numero_entree }}</strong>
                                                </td>
                                                <td>{{ $entree->date_entree->format('d/m/Y') }}</td>
                                                <td>{{ $entree->fournisseur->raison_sociale ?? $entree->fournisseur->nom }}
                                                </td>
                                                <td>{{ $entree->numero_facture ?? '-' }}</td>
                                                <td>{{ number_format($entree->montant_total, 0, ',', ' ') }}</td>
                                                <td>
                                                    @if ($entree->statut === 'recu')
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i>
                                                            Reçu</span>
                                                    @elseif($entree->statut === 'en_attente')
                                                        <span class="badge bg-warning"><i class="bi bi-hourglass-split"></i>
                                                            En Attente</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i>
                                                            Rejeté</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('gestions_entrees.show', $entree->id) }}"
                                                            class="btn btn-sm btn-success">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('gestions_entrees.edit', $entree->id) }}"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    </div>
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

    <!-- Modal Import CSV -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Importer des Entrées de Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('entrees.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Fichier CSV</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".csv,.txt" required>
                            <div class="form-text">
                                Format attendu: Code Fournisseur, Date Entrée, N° Facture, Observations, Statut, Code Article, Code Dépôt, Quantité, Prix Achat
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <strong>Note:</strong> Le fichier doit être au format CSV avec les colonnes dans l'ordre suivant:
                            <ul class="mb-0 mt-2">
                                <li><strong>Code Fournisseur</strong> - Le code du fournisseur (ex: FRS-00001)</li>
                                <li><strong>Date Entrée</strong> - Format YYYY-MM-DD (ex: 2025-12-27)</li>
                                <li><strong>Numéro Facture</strong> - Numéro de facture (optionnel)</li>
                                <li><strong>Observations</strong> - Remarques (optionnel)</li>
                                <li><strong>Statut</strong> - recu, en_attente ou rejete (défaut: recu)</li>
                                <li><strong>Code Article</strong> - Le code de l'article (ex: ART-00001)</li>
                                <li><strong>Code Dépôt</strong> - Le code du dépôt (ex: DEP-00001)</li>
                                <li><strong>Quantité</strong> - Quantité reçue (ex: 100)</li>
                                <li><strong>Prix Achat</strong> - Prix unitaire d'achat (ex: 5000)</li>
                            </ul>
                            <p class="mt-2 mb-0"><strong>Le numéro d'entrée sera généré automatiquement (ENT-00001, ENT-00002, etc.)</strong></p>
                            <p class="mt-2 mb-0 text-danger"><strong>Important:</strong> Les stocks seront automatiquement mis à jour pour les entrées avec statut "recu".</p>
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
