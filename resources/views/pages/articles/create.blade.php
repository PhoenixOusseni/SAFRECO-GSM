@extends('layouts.master')
@section('title', 'Gestion des Articles')
@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des articles</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Gestion des articles</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_articles.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>&nbsp; Retour à la liste des articles
            </a>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ajouter un article</h5>
                        <form action="{{ route('gestions_articles.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="small">Designation</label>
                                    <input type="text" class="form-control" id="nom" name="designation" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="reference" class="small">Référence</label>
                                    <input type="text" class="form-control" id="reference" name="reference" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="prix_achat" class="small">Prix d'Achat (FCFA)</label>
                                    <input type="number" class="form-control" id="prix_achat" name="prix_achat"
                                        step="0.01" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="prix_vente" class="small">Prix de Vente (FCFA)</label>
                                    <input type="number" class="form-control" id="prix_vente" name="prix_vente"
                                        step="0.01" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="small">Stock Disponible</label>
                                    <input type="number" class="form-control" id="stock" name="stock" value="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="seuil" class="small">Seuil de Réapprovisionnement</label>
                                    <input type="number" class="form-control" id="seuil" name="seuil" value="10" required>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i>&nbsp; Enregistrer l'article
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>&nbsp; Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
