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
            <a href="{{ route('gestions_fournisseurs.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>&nbsp; Ajouter un Fournisseur
            </a>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

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
@endsection
