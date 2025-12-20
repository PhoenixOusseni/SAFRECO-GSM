@extends('layouts.master')
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
            <a href="{{ route('gestions_articles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>&nbsp; Ajouter un article
            </a>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Liste des articles</h5>
                        <!-- Table with stripped rows -->
                        <table class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom de l'article</th>
                                    <th scope="col">Référence</th>
                                    <th scope="col">Prix d'Achat</th>
                                    <th scope="col">Prix de Vente</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($articles as $article)
                                    <tr>
                                        <th scope="row">{{ $article->code }}</th>
                                        <td>{{ $article->designation }}</td>
                                        <td>{{ $article->reference }}</td>
                                        <td>{{ number_format($article->prix_achat, 2, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($article->prix_vente, 2, ',', ' ') }} FCFA</td>
                                        <td class="text-center">
                                            <a href="{{ route('gestions_articles.edit', $article->id) }}" class="btn btn-sm btn-success">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
