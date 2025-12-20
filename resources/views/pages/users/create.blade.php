@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Créer un Utilisateur</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_utilisateurs.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item active">Créer</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_utilisateurs.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Formulaire de Création d'Utilisateur</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <strong>Erreurs de validation:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('gestions_utilisateurs.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <label for="nom" class="col-md-4 col-form-label">Nom <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                        id="nom" name="nom" value="{{ old('nom') }}" required>
                                    @error('nom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="prenom" class="col-md-4 col-form-label">Prénom <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                        id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                    @error('prenom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label">Email <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label">Mot de passe <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Minimum 8 caractères</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password_confirmation" class="col-md-4 col-form-label">Confirmer Mot de passe
                                    <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="role" class="col-md-4 col-form-label">Rôle <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-select @error('role') is-invalid @enderror" id="role"
                                        name="role" required>
                                        <option value="">-- Sélectionner un rôle --</option>
                                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>
                                            <i class="bi bi-person"></i> Utilisateur
                                        </option>
                                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>
                                            <i class="bi bi-person-check"></i> Manager
                                        </option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                            <i class="bi bi-shield-lock"></i> Administrateur
                                        </option>
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mx-0 mb-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Créer Utilisateur
                                    </button>
                                    <a href="{{ route('gestions_utilisateurs.index') }}" class="btn btn-secondary">
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
