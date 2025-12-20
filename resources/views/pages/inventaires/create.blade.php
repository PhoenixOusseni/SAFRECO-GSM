@extends('layouts.master')

@section('content')
<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Nouvel Inventaire</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('gestions_inventaires.index') }}">Inventaires</a></li>
                    <li class="breadcrumb-item active">Créer</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('gestions_inventaires.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations de l'Inventaire</h5>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <h6>Erreurs de validation:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('gestions_inventaires.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <label for="date_inventaire" class="col-sm-3 col-form-label">Date Inventaire <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control @error('date_inventaire') is-invalid @enderror" 
                                    id="date_inventaire" name="date_inventaire" 
                                    value="{{ old('date_inventaire', date('Y-m-d')) }}" required>
                                @error('date_inventaire')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="depot_id" class="col-sm-3 col-form-label">Dépôt <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select @error('depot_id') is-invalid @enderror" 
                                    id="depot_id" name="depot_id" required>
                                    <option value="">-- Sélectionner un dépôt --</option>
                                    @foreach($depots as $depot)
                                        <option value="{{ $depot->id }}" {{ old('depot_id') == $depot->id ? 'selected' : '' }}>
                                            {{ $depot->designation }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('depot_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Les articles en stock dans ce dépôt seront automatiquement chargés.
                                </small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="observation" class="col-sm-3 col-form-label">Observation</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('observation') is-invalid @enderror" 
                                    id="observation" name="observation" rows="3">{{ old('observation') }}</textarea>
                                @error('observation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Créer l'Inventaire
                                </button>
                                <a href="{{ route('gestions_inventaires.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Annuler
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
