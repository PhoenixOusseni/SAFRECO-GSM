@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Détails Utilisateur: {{ $user->name }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_utilisateurs.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item active">{{ $user->nom }} {{ $user->prenom }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_utilisateurs.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('gestions_utilisateurs.edit', $user->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('gestions_utilisateurs.destroy', $user->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <!-- Informations Générales -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations Générales</h5>

                        <div class="row mb-3">
                            <label class="col-md-4 fw-bold">Nom Complet:</label>
                            <div class="col-md-8">
                                {{ $user->nom }} {{ $user->prenom }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 fw-bold">Email:</label>
                            <div class="col-md-8">
                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 fw-bold">Rôle:</label>
                            <div class="col-md-8">
                                @if ($user->role === 'admin')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-shield-lock"></i> Administrateur
                                    </span>
                                @elseif ($user->role === 'manager')
                                    <span class="badge bg-warning">
                                        <i class="bi bi-person-check"></i> Manager
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-person"></i> Utilisateur
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 fw-bold">Statut Email:</label>
                            <div class="col-md-8">
                                @if ($user->email_verified_at)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Vérifié
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Non vérifié
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de Dates -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations de Compte</h5>

                        <div class="mb-3">
                            <label class="fw-bold small text-muted">Date de Création:</label>
                            <p class="mb-0">
                                {{ $user->created_at }}
                            </p>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="fw-bold small text-muted">Dernière Modification:</label>
                            <p class="mb-0">
                                {{ $user->updated_at }}
                            </p>
                        </div>

                        <hr>

                        <div>
                            <label class="fw-bold small text-muted">ID Utilisateur:</label>
                            <p class="mb-0">
                                <code>{{ $user->id }}</code>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Actions Rapides</h5>

                        <a href="{{ route('gestions_utilisateurs.edit', $user->id) }}" class="btn btn-warning w-100 mb-2">
                            <i class="bi bi-pencil"></i> Modifier les informations
                        </a>

                        <form action="{{ route('gestions_utilisateurs.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash"></i> Supprimer le compte
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
