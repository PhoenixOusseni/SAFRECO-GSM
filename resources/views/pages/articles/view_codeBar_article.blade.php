@extends('layouts.master')

@section('content')
    <!-- CDN JsBarcode -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .barcode-item {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            body {
                background: white !important;
            }
        }

        .barcode-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .barcode-item {
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 8px;
            background: white;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .barcode-item h6 {
            margin-bottom: 10px;
            color: #333;
            font-size: 14px;
            font-weight: 600;
        }

        .barcode-item .code-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .barcode-item svg {
            margin: 10px auto;
        }
    </style>

    <div class="pagetitle no-print">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>Codes-barres des articles</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gestions_articles.index') }}">Articles</a></li>
                        <li class="breadcrumb-item active">Codes-barres</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gestions_articles.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('articles.print_code_barre') }}" class="btn btn-success" target="_blank">
                    <i class="bi bi-printer"></i>
                </a>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title no-print">
                            Liste des codes-barres
                            <span class="badge bg-primary">{{ count($articles) }} article(s)</span>
                        </h5>

                        @if (count($articles) > 0)
                            <div class="barcode-container">
                                @foreach ($articles as $article)
                                    @if ($article->code_barre)
                                        <div class="barcode-item">
                                            <h6>{{ $article->designation }}</h6>
                                            <div class="mb-0 text-center">
                                                <img src="{{ asset('images/images.jpg') }}" alt="logo safreco" style="width: 125px;">
                                            </div>
                                            <svg class="barcode" data-code="{{ $article->code_barre }}"
                                                data-designation="{{ $article->designation }}">
                                            </svg>
                                            <h3 class="code-text text-success mb-2">Prix: {{ number_format($article->prix_vente, 0, ',', ' ') }} FCFA</h3>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                Aucun article avec code-barres trouvé.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Générer tous les codes-barres
            document.querySelectorAll('.barcode').forEach(function(svg) {
                const code = svg.getAttribute('data-code');
                const designation = svg.getAttribute('data-designation');

                try {
                    JsBarcode(svg, code, {
                        format: "CODE128",
                        width: 2,
                        height: 60,
                        displayValue: true,
                        fontSize: 14,
                        margin: 5
                    });
                } catch (e) {
                    console.error('Erreur génération code-barres pour:', designation, e);
                    svg.parentElement.innerHTML += '<p class="text-danger">Erreur de génération</p>';
                }
            });
        });

        // Optimisation pour l'impression
        window.onbeforeprint = function() {
            document.body.style.background = 'white';
        };
    </script>
@endsection
