@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>{{ isset($achat) ? 'Modifier Achat' : 'Nouvel Achat' }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_achats.index') }}">Achats</a></li>
                        <li class="breadcrumb-item active">{{ isset($achat) ? 'Modifier' : 'Créer' }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ isset($achat) ? 'Modification de l\'Achat' : 'Créer un nouvel Achat' }}</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading">Erreurs de validation</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form
                            action="{{ isset($achat) ? route('gestions_achats.update', $achat->id) : route('gestions_achats.store') }}"
                            method="POST">
                            @csrf
                            @if (isset($achat))
                                @method('PUT')
                            @endif
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="numero_achat" class="small">code</label>
                                    <input type="text" class="form-control" id="numero_achat" name="numero_achat" disabled
                                        value="{{ isset($achat) ? $achat->numero_achat : 'Auto-généré' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="small">Date d'Achat *</label>
                                    <input type="date" class="form-control" name="date_achat"
                                        value="{{ isset($achat) ? $achat->date_achat->format('Y-m-d') : old('date_achat', date('Y-m-d')) }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="designation" class="small">Designation</label>
                                    <input type="text" class="form-control" id="designation" name="designation"
                                        value="{{ isset($achat) ? $achat->designation : old('designation') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fournisseur_id" class="small">Fournisseur *</label>
                                    <select class="form-select" id="fournisseur_id" name="fournisseur_id" required>
                                        <option value="">-- Sélectionner un fournisseur --</option>
                                        @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}"
                                                {{ (isset($achat) && $achat->fournisseur_id == $fournisseur->id) ? 'selected' : '' }}>
                                                {{ $fournisseur->raison_sociale ?? $fournisseur->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="montant_total" class="small">Montant Total *</label>
                                    <input type="number" step="0.01" class="form-control" name="montant_total"
                                        value="{{ isset($achat) ? $achat->montant_total : old('montant_total') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> {{ isset($achat) ? 'Modifier' : 'Enregistrer' }}
                                    </button>
                                    <a href="{{ route('gestions_achats.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
