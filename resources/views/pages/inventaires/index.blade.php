@extends('layouts.master')

@section('content')
<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Gestion des Inventaires</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Inventaires</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('gestions_inventaires.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nouvel Inventaire
        </a>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Liste des Inventaires</h5>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>N° Inventaire</th>
                                <th>Date</th>
                                <th>Dépôt</th>
                                <th>Utilisateur</th>
                                <th>Statut</th>
                                <th>Écart Total</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventaires as $inventaire)
                            <tr>
                                <td><span class="badge bg-primary">{{ $inventaire->numero_inventaire }}</span></td>
                                <td>{{ $inventaire->date_inventaire->format('d/m/Y') }}</td>
                                <td>{{ $inventaire->depot->designation ?? 'N/A' }}</td>
                                <td>{{ $inventaire->user->name ?? 'N/A' }}</td>
                                <td>
                                    @if($inventaire->statut == 'valide')
                                        <span class="badge bg-success">Validé</span>
                                    @elseif($inventaire->statut == 'en_cours')
                                        <span class="badge bg-warning">En cours</span>
                                    @else
                                        <span class="badge bg-danger">Annulé</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-{{ $inventaire->ecart_total_quantite >= 0 ? 'success' : 'danger' }}">
                                        {{ $inventaire->ecart_total_quantite > 0 ? '+' : '' }}{{ $inventaire->ecart_total_quantite }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('gestions_inventaires.show', $inventaire->id) }}" class="btn btn-sm btn-success" title="Voir"><i class="bi bi-eye"></i></a>
                                    @if($inventaire->statut != 'valide')
                                        <a href="{{ route('gestions_inventaires.edit', $inventaire->id) }}" class="btn btn-sm btn-warning" title="Modifier"><i class="bi bi-pencil"></i></a>
                                    @endif
                                    <a href="{{ route('gestions_inventaires.print', $inventaire->id) }}" class="btn btn-sm btn-info" title="Imprimer" target="_blank"><i class="bi bi-printer"></i></a>
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
