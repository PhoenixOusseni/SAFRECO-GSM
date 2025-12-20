@extends('layouts.master')
@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Depots</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Gestion des Depots</li>
                    </ol>
                </nav>
            </div>
            <div class="mx-0">
                <a href="{{ route('gestions_depots.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>&nbsp; Retour à la liste des Depots
                </a>
            </div>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ajouter un Nouveau Depot</h5>
                        <form action="{{ route('gestions_depots.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="designation" class="small">Code du Depot</label>
                                <input type="text" class="form-control" id="code" name="code" disabled required>
                            </div>
                            <div class="mb-3">
                                <label for="designation" class="small">Désignation du Depot</label>
                                <input type="text" class="form-control" id="designation" name="designation" required>
                            </div>
                            <div class="mb-3">
                                <label for="localisation" class="small">Localisation</label>
                                <input type="text" class="form-control" id="localisation" name="localisation" required>
                            </div>
                            <div class="mb-3">
                                <label for="responsable" class="small">Responsable</label>
                                <input type="text" class="form-control" id="responsable" name="responsable" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="small">Contact</label>
                                <input type="number" class="form-control" id="contact" name="contact" required>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i>&nbsp; Enregistrer le Depot
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
