@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Mon Profil</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                <li class="breadcrumb-item active">Profil</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <!-- Avatar par défaut avec initiales -->
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white mb-3"
                            style="width: 120px; height: 120px; font-size: 48px; font-weight: bold;">
                            {{ strtoupper(substr($user->prenom, 0, 1) . substr($user->nom, 0, 1)) }}
                        </div>
                        <h2>{{ $user->prenom }} {{ $user->nom }}</h2>
                        <h3 class="text-muted">{{ ucfirst($user->role) }}</h3>
                        <div class="social-links mt-2">
                            <span class="badge bg-success">
                                <i class="bi bi-circle-fill"></i> Actif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations Rapides</h5>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Email:</div>
                            <div class="col-sm-8">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Rôle:</div>
                            <div class="col-sm-8">
                                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Membre depuis:</div>
                            <div class="col-sm-8">{{ $user->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Dernière connexion:</div>
                            <div class="col-sm-8">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">
                                    <i class="bi bi-person"></i> Aperçu
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">
                                    <i class="bi bi-pencil-square"></i> Modifier le Profil
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">
                                    <i class="bi bi-shield-lock"></i> Changer le Mot de Passe
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">
                            <!-- Onglet Aperçu -->
                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Détails du Profil</h5>

                                <div class="row mb-3">
                                    <div class="col-lg-3 col-md-4 label fw-bold">Nom Complet</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->prenom }} {{ $user->nom }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-3 col-md-4 label fw-bold">Prénom</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->prenom }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-3 col-md-4 label fw-bold">Nom</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->nom }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-3 col-md-4 label fw-bold">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-3 col-md-4 label fw-bold">Rôle</div>
                                    <div class="col-lg-9 col-md-8">
                                        <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-3 col-md-4 label fw-bold">Date de création</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->created_at->format('d/m/Y à H:i') }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-3 col-md-4 label fw-bold">Dernière modification</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->updated_at->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>

                            <!-- Onglet Modifier le Profil -->
                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <h5 class="card-title">Modifier les Informations</h5>

                                <form action="{{ route('utilisateurs.updateProfil', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <label for="prenom" class="col-md-4 col-lg-3 col-form-label">Prénom</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                                id="prenom" value="{{ old('prenom', $user->prenom) }}" required>
                                            @error('prenom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="nom" class="col-md-4 col-lg-3 col-form-label">Nom</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                                id="nom" value="{{ old('nom', $user->nom) }}" required>
                                            @error('nom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mx-0 mt-5">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Enregistrer les Modifications
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Onglet Changer le Mot de Passe -->
                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <h5 class="card-title">Changer le Mot de Passe</h5>

                                <form action="{{ route('utilisateurs.updatePassword', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Champs cachés pour préserver les données actuelles -->
                                    <input type="hidden" name="nom" value="{{ $user->nom }}">
                                    <input type="hidden" name="prenom" value="{{ $user->prenom }}">
                                    <input type="hidden" name="email" value="{{ $user->email }}">

                                    <div class="row mb-3">
                                        <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Mot de Passe Actuel</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="password" name="current_password"
                                                class="form-control @error('current_password') is-invalid @enderror" id="current_password">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Laissez vide si vous ne voulez pas changer le mot de passe</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password" class="col-md-4 col-lg-3 col-form-label">Nouveau Mot de Passe</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Au moins 8 caractères</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">Confirmer le Mot de Passe</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="password" name="password_confirmation"
                                                class="form-control" id="password_confirmation">
                                        </div>
                                    </div>

                                    <div class="alert alert-warning" role="alert">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <strong>Attention:</strong> Assurez-vous de bien retenir votre nouveau mot de passe.
                                        Vous serez déconnecté après le changement.
                                    </div>

                                    <div class="mx-0 mt-5">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="bi bi-shield-lock"></i> Changer le Mot de Passe
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div><!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
