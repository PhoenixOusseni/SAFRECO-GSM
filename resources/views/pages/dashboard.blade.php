@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Tableau de bord</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                <li class="breadcrumb-item active">Tableau de bord</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- Ventes Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Ventes <span>| Ce mois</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart-check"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($ventes_mois, 0, ',', ' ') }} FCFA</h6>
                                        <span class="text-success small pt-1 fw-bold">{{ $total_stats['ventes'] }}</span>
                                        <span class="text-muted small pt-2 ps-1">transactions</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Ventes Card -->

                    <!-- Achats Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Achats <span>| Ce mois</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-bag-check"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($achats_mois, 0, ',', ' ') }} FCFA</h6>
                                        <span class="text-info small pt-1 fw-bold">{{ $total_stats['achats'] }}</span>
                                        <span class="text-muted small pt-2 ps-1">achats</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Achats Card -->

                    <!-- Encaissements Card -->
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Encaissements <span>| Ce mois</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cash-coin"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($encaissements_mois, 0, ',', ' ') }} FCFA</h6>
                                        <span class="text-warning small pt-1 fw-bold">{{ $total_stats['encaissements'] }}</span>
                                        <span class="text-muted small pt-2 ps-1">encaissements</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Encaissements Card -->

                    <!-- Décaissements Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card warning-card">
                            <div class="card-body">
                                <h5 class="card-title">Décaissements <span>| Ce mois</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cash-flow"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($decaissements_mois, 0, ',', ' ') }} FCFA</h6>
                                        <span class="text-danger small pt-1 fw-bold">{{ $total_stats['decaissements'] }}</span>
                                        <span class="text-muted small pt-2 ps-1">décaissements</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Décaissements Card -->

                    <!-- Clients Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card">
                            <div class="card-body">
                                <h5 class="card-title">Clients</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $total_stats['clients'] }}</h6>
                                        <span class="text-muted small pt-2 ps-1">clients enregistrés</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Clients Card -->

                    <!-- Articles Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card">
                            <div class="card-body">
                                <h5 class="card-title">Articles</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $total_stats['articles'] }}</h6>
                                        <span class="text-muted small pt-2 ps-1">articles en stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Articles Card -->

                    <!-- Articles avec Faible Stock -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Articles avec Stock Faible</h5>
                                @if($stock_faible->count() > 0)
                                    <div class="row">
                                        @foreach($stock_faible as $article)
                                            <div class="col-md-6 mb-3">
                                                <div class="alert alert-warning border" role="alert">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                            <h6 class="alert-heading mb-1">{{ $article->designation }}</h6>
                                            <small class="d-block">Dépôt: <strong>{{ $article->depot->nom ?? 'N/A' }}</strong></small>
                                            <small class="d-block">Stock: <strong class="text-danger">{{ $article->quantite_disponible }}</strong> / {{ $article->quantite_minimale }}</small>
                                        </div>
                                        <span class="badge bg-warning text-dark">{{ round((($article->quantite_disponible / $article->quantite_minimale) * 100), 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-success" role="alert">
                        ✓ Tous les stocks sont normaux
                    </div>
                @endif
                </div>
            </div><!-- End Stock Faible -->

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">Ventes Récentes</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Client</th>
                                            <th scope="col">Montant</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ventes_recentes as $vente)
                                            <tr>
                                                <th scope="row"><a href="#">#{{ $vente->id }}</a></th>
                                                <td>{{ $vente->client->nom ?? 'Non spécifié' }}</td>
                                                <td class="fw-bold">{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</td>
                                                <td>{{ $vente->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="#" class="btn badge bg-success">Voir</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Aucune vente récente</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- End Recent Sales -->

                    <!-- Top Selling Articles -->
                    <div class="col-12">
                        <div class="card top-selling overflow-auto">
                            <div class="card-body pb-0">
                                <h5 class="card-title">Top 5 Articles Vendus</h5>
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th scope="col">Article</th>
                                            <th scope="col">Quantité</th>
                                            <th scope="col">Montant Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($top_articles_ventes as $article)
                                            <tr>
                                                <td>
                                                    <a href="#" class="text-primary fw-bold">{{ $article->designation }}</a>
                                                </td>
                                                <td><span class="badge bg-success">{{ $article->total_quantite }}</span></td>
                                                <td>{{ number_format($article->total_montant, 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Aucune vente</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Top Selling Articles -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-12">

                <!-- Top Articles Achats -->
                <div class="card">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Top 5 Articles Achetés</h5>

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">Article</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($top_articles_achats as $article)
                                    <tr>
                                        <td>
                                            <a href="#" class="text-primary fw-bold">{{ $article->designation }}</a>
                                        </td>
                                        <td><small class="badge bg-info">{{ $article->total_quantite }}</small></td>
                                        <td><small>{{ number_format($article->total_montant, 0, ',', ' ') }} FCFA</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Aucun achat</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div><!-- End Top Articles Achats -->

                <!-- Recent Purchases -->
                <div class="card">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Achats Récents</h5>

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Fournisseur</th>
                                    <th scope="col">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($achats_recents as $achat)
                                    <tr>
                                        <td><a href="#">#{{ $achat->id }}</a></td>
                                        <td><small>{{ $achat->fournisseur->nom ?? 'Non spécifié' }}</small></td>
                                        <td><small class="fw-bold">{{ number_format($achat->montant_total, 0, ',', ' ') }} FCFA</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Aucun achat récent</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div><!-- End Recent Purchases -->

                <!-- Today Summary -->
                <div class="card">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Résumé d'Aujourd'hui</h5>

                        <div class="mb-3">
                            <p class="text-muted mb-1">Ventes</p>
                            <h6 class="text-success">{{ $today_stats['ventes_count'] }} transactions</h6>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted mb-1">Achats</p>
                            <h6 class="text-info">{{ $today_stats['achats_count'] }} transactions</h6>
                        </div>

                        <div>
                            <p class="text-muted mb-1">Encaissements</p>
                            <h6 class="text-warning">{{ number_format($today_stats['encaissements'], 0, ',', ' ') }} FCFA</h6>
                        </div>

                    </div>
                </div><!-- End Today Summary -->

            </div><!-- End Right side columns -->
        </div>
    </section>
@endsection
