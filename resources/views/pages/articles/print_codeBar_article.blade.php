<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression Codes-barres - Articles</title>

    <!-- CDN JsBarcode -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: white;
            padding: 10mm;
        }

        .print-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .print-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .print-header p {
            font-size: 14px;
            color: #666;
        }

        /* Grille d'étiquettes - Format A4 */
        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10mm;
            width: 100%;
        }

        /* Style des étiquettes individuelles */
        .barcode-label {
            border: 2px solid #333;
            padding: 8mm;
            background: white;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
            min-height: 70mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .barcode-label .article-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
            line-height: 1.2;
            max-height: 40px;
            overflow: hidden;
        }

        .barcode-label .article-code {
            font-size: 11px;
            color: #555;
            margin-bottom: 8px;
        }

        .barcode-label svg {
            margin: 10px auto;
            max-width: 100%;
        }

        .barcode-label .barcode-text {
            font-size: 12px;
            font-weight: bold;
            margin: 5px 0;
            color: #000;
        }

        .barcode-label .price {
            font-size: 16px;
            font-weight: bold;
            color: #00b050;
            margin-top: 8px;
        }

        /* Optimisation impression */
        @media print {
            body {
                padding: 5mm;
            }

            .print-header {
                display: none;
            }

            .barcode-grid {
                gap: 5mm;
            }

            .barcode-label {
                border: 1px solid #000;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }

        /* Message si pas d'articles */
        .no-data {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #666;
        }

        /* Boutons d'action (cachés à l'impression) */
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .action-buttons button {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .btn-print {
            background-color: #0d6efd;
            color: white;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        @media print {
            .action-buttons {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <!-- Boutons d'action -->
    <div class="action-buttons">
        <button class="btn-print" onclick="window.print()">
            🖨️ Imprimer
        </button>
        <button class="btn-back" onclick="window.history.back()">
            ← Retour
        </button>
    </div>

    <!-- En-tête (caché à l'impression) -->
    <div class="print-header">
        <h1>📦 CODES-BARRES DES ARTICLES</h1>
        <p>Document généré le {{ date('d/m/Y à H:i') }}</p>
        <p><strong>Total:</strong> {{ count($articles) }} article(s)</p>
    </div>

    @if (count($articles) > 0)
        <!-- Grille d'étiquettes -->
        <div class="barcode-grid">
            @foreach ($articles as $article)
                @if ($article->code_barre)
                    <div class="barcode-label">
                        <div class="article-name">{{ Str::limit($article->designation, 50) }}</div>

                        <div class="mb-0 text-center">
                            <img src="{{ asset('images/images.jpg') }}" alt="logo safreco" style="width: 125px;">
                        </div>

                        <svg class="barcode" data-code="{{ $article->code_barre }}">
                        </svg>

                        <div class="barcode-text">{{ $article->code_barre }}</div>

                        @if ($article->prix_vente)
                            <div class="price">
                                {{ number_format($article->prix_vente, 0, ',', ' ') }} FCFA
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="no-data">
            <p>❌ Aucun article avec code-barres disponible</p>
        </div>
    @endif

    <script>
        // Générer tous les codes-barres au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const barcodes = document.querySelectorAll('.barcode');

            barcodes.forEach(function(svg) {
                const code = svg.getAttribute('data-code');

                if (code) {
                    try {
                        JsBarcode(svg, code, {
                            format: "CODE128",
                            width: 2,
                            height: 50,
                            displayValue: false, // Masqué car affiché manuellement
                            fontSize: 12,
                            margin: 5,
                            background: "#ffffff",
                            lineColor: "#000000"
                        });
                    } catch (e) {
                        console.error('Erreur génération code-barres:', code, e);
                        svg.parentElement.innerHTML += '<p style="color:red;font-size:10px;">Erreur</p>';
                    }
                }
            });

            // Imprimer automatiquement après 1 seconde (optionnel)
            // setTimeout(function() { window.print(); }, 1000);
        });

        // Gestion impression
        window.onbeforeprint = function() {
            document.body.style.background = 'white';
        };

        window.onafterprint = function() {
            // Optionnel: Fermer la fenêtre après impression
            // window.close();
        };
    </script>
</body>

</html>
