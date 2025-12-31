@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Tableau d'amortissement</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                <li class="breadcrumb-item active">Tableau d'amortissement</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center" style="color: #1b305e; font-weight: bold; font-size: 16px;">
                            TABLEAU DES IMMOBILISATIONS ET DES AMORTISSEMENTS
                        </h5>

                        <!-- Tableau d'amortissement -->
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-size: 11px;">
                                <thead>
                                    <tr style="background-color: #d0e4f5;">
                                        <th rowspan="2" class="text-center align-middle">NUMERO DE COMPTE</th>
                                        <th rowspan="2" class="text-center align-middle">DESIGNATION DES IMMOBILISATIONS</th>
                                        <th rowspan="2" class="text-center align-middle">TAUX<br>AMORT. %</th>
                                        <th rowspan="2" class="text-center align-middle">DATE MISE<br>SERVICE</th>
                                        <th rowspan="2" class="text-center align-middle">VALEUR<br>D'ACQUISITION</th>
                                        <th colspan="3" class="text-center">AMORTISSEMENTS</th>
                                        <th rowspan="2" class="text-center align-middle">VALEUR<br>RESIDUELLE</th>
                                        <th rowspan="2" class="text-center align-middle">Valeurs nette</th>
                                    </tr>
                                    <tr style="background-color: #d0e4f5;">
                                        <th class="text-center">ANTERIEURS</th>
                                        <th class="text-center">DE L'EXERCICE</th>
                                        <th class="text-center">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalAcquisition = 0;
                                        $totalAnterieurs = 0;
                                        $totalExercice = 0;
                                        $totalAmortissements = 0;
                                        $totalResiduelle = 0;
                                    @endphp

                                    @forelse($articlesData as $famille => $articles)
                                        <!-- Ligne de catégorie -->
                                        <tr style="background-color: #f0f0f0;">
                                            <td colspan="10" style="font-weight: bold;">
                                                {{ strtoupper($famille) }}
                                            </td>
                                        </tr>

                                        @php
                                            $subtotalAcquisition = 0;
                                            $subtotalAnterieurs = 0;
                                            $subtotalExercice = 0;
                                            $subtotalAmortissements = 0;
                                            $subtotalResiduelle = 0;
                                        @endphp

                                        <!-- Lignes des articles -->
                                        @foreach($articles as $article)
                                            @php
                                                $subtotalAcquisition += $article['valeur_acquisition'];
                                                $subtotalAnterieurs += $article['amortissements_anterieurs'];
                                                $subtotalExercice += $article['amortissement_exercice'];
                                                $subtotalAmortissements += $article['total_amortissements'];
                                                $subtotalResiduelle += $article['valeur_residuelle'];
                                            @endphp
                                            <tr>
                                                <td>{{ $article['numero_compte'] }}</td>
                                                <td>{{ $article['designation'] }}</td>
                                                <td class="text-center">{{ number_format($article['taux_amortissement'], 1) }}%</td>
                                                <td class="text-center">
                                                    {{ $article['date_mise_service'] ? \Carbon\Carbon::parse($article['date_mise_service'])->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="text-end">{{ number_format($article['valeur_acquisition'], 0, ',', ' ') }}</td>
                                                <td class="text-end">{{ number_format($article['amortissements_anterieurs'], 0, ',', ' ') }}</td>
                                                <td class="text-end">{{ number_format($article['amortissement_exercice'], 0, ',', ' ') }}</td>
                                                <td class="text-end">{{ number_format($article['total_amortissements'], 0, ',', ' ') }}</td>
                                                <td class="text-end">{{ number_format($article['valeur_residuelle'], 0, ',', ' ') }}</td>
                                                <td class="text-end">{{ number_format($article['valeur_residuelle'], 0, ',', ' ') }}</td>
                                            </tr>
                                        @endforeach

                                        <!-- Sous-total par catégorie -->
                                        <tr style="background-color: #e8f4f8; font-weight: bold;">
                                            <td colspan="4" class="text-end">TOTAL {{ strtoupper($famille) }}</td>
                                            <td class="text-end">{{ number_format($subtotalAcquisition, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($subtotalAnterieurs, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($subtotalExercice, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($subtotalAmortissements, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($subtotalResiduelle, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($subtotalResiduelle, 0, ',', ' ') }}</td>
                                        </tr>

                                        @php
                                            $totalAcquisition += $subtotalAcquisition;
                                            $totalAnterieurs += $subtotalAnterieurs;
                                            $totalExercice += $subtotalExercice;
                                            $totalAmortissements += $subtotalAmortissements;
                                            $totalResiduelle += $subtotalResiduelle;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">
                                                <i class="bi bi-info-circle"></i>
                                                Aucune immobilisation trouvée
                                            </td>
                                        </tr>
                                    @endforelse

                                    @if($articlesData->isNotEmpty())
                                        <!-- Ligne de total général -->
                                        <tr style="background-color: #d0e4f5; font-weight: bold; font-size: 12px;">
                                            <td colspan="4" class="text-end">TOTAL GÉNÉRAL</td>
                                            <td class="text-end">{{ number_format($totalAcquisition, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($totalAnterieurs, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($totalExercice, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($totalAmortissements, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($totalResiduelle, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($totalResiduelle, 0, ',', ' ') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="mt-3">
                            <a href="{{ route('amortissements.print') }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-printer"></i> Imprimer
                            </a>
                            <button onclick="exportToExcel()" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Exporter Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @media print {
            .btn, .breadcrumb, .pagetitle nav { display: none; }
            table { font-size: 10px; }
        }
    </style>

    <script>
        function exportToExcel() {
            var table = document.querySelector('table');
            var html = table.outerHTML;
            var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            var downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);
            downloadLink.href = url;
            downloadLink.download = 'tableau_amortissement.xls';
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
@endsection
