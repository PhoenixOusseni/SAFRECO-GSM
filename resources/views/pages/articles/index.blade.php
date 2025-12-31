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
                                    <th scope="col">Code-barres</th>
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
                                        <td>
                                            @if ($article->code_barre)
                                                <span class="badge bg-info">{{ $article->code_barre }}</span>
                                            @else
                                                <span class="badge bg-secondary">Non généré</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($article->prix_achat, 2, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($article->prix_vente, 2, ',', ' ') }} FCFA</td>
                                        <td class="text-center">
                                            @if ($article->code_barre)
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    onclick="showBarcode('{{ $article->code_barre }}', '{{ $article->designation }}', '{{ number_format($article->prix_vente, 0, ',', ' ') }}')"
                                                    title="Voir le code-barres">
                                                    <i class="bi bi-upc-scan"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('gestions_articles.edit', $article->id) }}"
                                                class="btn btn-sm btn-success">
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

    <!-- Modal pour afficher le code-barres -->
    <div class="modal fade" id="barcodeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Code-barres de l'article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="bg-white p-3 rounded">
                        <svg id="barcodeDisplay"></svg>
                    </div>
                    <p class="mt-3 mb-0">
                        <strong id="barcodeText"></strong>
                    </p>
                    <p id="articleName" class="text-muted mb-0"></p>
                    <p id="articlePrice" class="fw-bold mb-0"></p>
                </div>
                <hr>
                <div class="mx-3 mb-3 mt-5">
                    <button type="button" class="btn btn-primary" onclick="printBarcodeFromModal()">
                        <i class="bi bi-printer"></i> Imprimer
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadBarcodeFromModal()">
                        <i class="bi bi-download"></i> Télécharger
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclure la bibliothèque JsBarcode -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <script>
        let currentBarcode = '';
        let currentArticleName = '';
        let currentArticlePrice = '';

        // Fonction pour afficher le code-barres dans la modale
        function showBarcode(barcode, articleName, articlePrice) {
            currentBarcode = barcode;
            currentArticleName = articleName;
            currentArticlePrice = articlePrice;

            // Afficher les informations
            document.getElementById('barcodeText').textContent = barcode;
            document.getElementById('articleName').textContent = articleName;
            document.getElementById('articlePrice').textContent = articlePrice + ' FCFA';

            // Générer le code-barres
            JsBarcode("#barcodeDisplay", barcode, {
                format: "CODE128",
                width: 2,
                height: 80,
                displayValue: false,
                margin: 10
            });

            // Afficher la modale
            const modal = new bootstrap.Modal(document.getElementById('barcodeModal'));
            modal.show();
        }

        // Fonction pour imprimer le code-barres depuis la modale
        function printBarcodeFromModal() {
            const printWindow = window.open('', '', 'width=600,height=400');
            const barcodeContent = document.getElementById('barcodeDisplay').outerHTML;

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Code-barres - ${currentBarcode}</title>
                    <style>
                        body {
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            margin: 0;
                            font-family: Arial, sans-serif;
                        }
                        .info {
                            margin-top: 20px;
                            text-align: center;
                        }
                        @media print {
                            body {
                                margin: 20px;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${barcodeContent}
                    <div class="info">
                        <h3>${currentBarcode}</h3>
                        <p>${currentArticleName}</p>
                        <p><strong>Prix: ${currentArticlePrice} FCFA</strong></p>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
                    <script>
                        JsBarcode("#barcodeDisplay", "${currentBarcode}", {
                            format: "CODE128",
                            width: 2,
                            height: 80,
                            displayValue: false,
                            margin: 10
                        });
                        setTimeout(() => {
                            window.print();
                            window.close();
                        }, 500);
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        // Fonction pour télécharger le code-barres depuis la modale
        function downloadBarcodeFromModal() {
            const svg = document.getElementById('barcodeDisplay');
            const svgData = new XMLSerializer().serializeToString(svg);
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();

            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0);

                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'code-barre-' + currentBarcode + '.png';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                });
            };

            img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
        }
    </script>
@endsection
