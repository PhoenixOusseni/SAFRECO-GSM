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
            <a href="{{ route('gestions_depots.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>&nbsp; Ajouter un Nouveau Depot
            </a>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Liste des Depots</h5>
                        <table class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Designation</th>
                                    <th scope="col">Localisation</th>
                                    <th scope="col">Responsable</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($depots as $depot)
                                    <tr>
                                        <th scope="row">{{ $depot->code }}</th>
                                        <td>{{ $depot->designation }}</td>
                                        <td>{{ $depot->localisation }}</td>
                                        <td>{{ $depot->responsable }}</td>
                                        <td>{{ $depot->contact }}</td>
                                        <td>
                                            <a href="{{ route('gestions_depots.show', $depot->id) }}" class="btn btn-sm btn-success">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('gestions_depots.edit', $depot->id) }}" class="btn btn-sm btn-warning">
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
