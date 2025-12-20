@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Information de la société</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Information de la société</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section profile">
        <div class="row">
            @foreach ($entetes as $item)
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            @if ($item->logo)
                                <img src="{{ asset('storage/' . $item->logo) }}" alt="Logo" class="rounded-circle">
                            @else
                                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="rounded-circle">
                            @endif
                            <h2>{{ $item->titre }}</h2>
                            <h3 class="text-center">{{ $item->sous_titre }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Vue d'ensemble</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Modifier
                                        les informations</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h5 class="card-title">A propos</h5>
                                    <p class="small fst-italic">
                                        {{ $item->description }}
                                    </p>
                                    <h5 class="card-title">Details</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Intitulé</div>
                                        <div class="col-lg-9 col-md-8">{{ $item->titre }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Slogan</div>
                                        <div class="col-lg-9 col-md-8">{{ $item->sous_titre }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8">{{ $item->email }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Téléphone</div>
                                        <div class="col-lg-9 col-md-8">{{ $item->telephone }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Adresse</div>
                                        <div class="col-lg-9 col-md-8">{{ $item->adresse }}</div>
                                    </div>
                                </div>

                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                    <!-- Profile Edit Form -->
                                    <form method="POST" action="{{ route('entetes.update', $item->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row mb-3">
                                            <label for="logo" class="col-md-4 col-lg-3 col-form-label">Logo
                                                images</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="logo" type="file" class="form-control" id="logo" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="titre" class="col-md-4 col-lg-3 col-form-label">Intitulé</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="titre" type="text" class="form-control" id="titre"
                                                    value="{{ $item->titre }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="sous_titre"
                                                class="col-md-4 col-lg-3 col-form-label">Slogan</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="sous_titre" type="text" class="form-control"
                                                    id="sous_titre" value="{{ $item->sous_titre }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="about"
                                                class="col-md-4 col-lg-3 col-form-label">Description</label>
                                            <div class="col-md-8 col-lg-9">
                                                <textarea name="description" class="form-control" id="description" style="height: 100px">{{ $item->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="email" type="text" class="form-control" id="email"
                                                    value="{{ $item->email }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="telephone"
                                                class="col-md-4 col-lg-3 col-form-label">Téléphone</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="telephone" type="text" class="form-control" id="telephone"
                                                    value="{{ $item->telephone }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="adresse" class="col-md-4 col-lg-3 col-form-label">Adresse</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="adresse" type="text" class="form-control" id="adresse"
                                                    value="{{ $item->adresse }}">
                                            </div>
                                        </div>

                                        <div class="text-start mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-save"></i>&nbsp; Enregistrer les modifications
                                            </button>
                                        </div>
                                    </form><!-- End Profile Edit Form -->
                                </div>
                            </div><!-- End Bordered Tabs -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
