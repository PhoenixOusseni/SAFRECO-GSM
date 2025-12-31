<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau d'Amortissement - Impression</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: white;
            padding: 15mm;
            font-size: 11px;
        }

        .print-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1b305e;
        }

        .print-header h1 {
            font-size: 18px;
            color: #1b305e;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .print-header .info {
            font-size: 11px;
            color: #666;
            margin: 5px 0;
        }

        /* Style du tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: left;
        }

        table thead th {
            background-color: #d0e4f5;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            color: #000;
        }

        table tbody tr.category-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        table tbody tr.subtotal-row {
            background-color: #e8f4f8;
            font-weight: bold;
        }

        table tbody tr.total-row {
            background-color: #d0e4f5;
            font-weight: bold;
            font-size: 11px;
        }

        .text-center {
            text-align: center !important;
        }

        .text-end {
            text-align: right !important;
        }

        /* Pied de page */
        .print-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ccc;
            font-size: 10px;
            color: #666;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }

        /* Boutons d'action */
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

        /* Optimisation impression */
        @media print {
            body {
                padding: 10mm;
            }

            .action-buttons {
                display: none !important;
            }

            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
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

    <!-- En-tête -->
    <div class="print-header">
        <h1>📊 TABLEAU DES IMMOBILISATIONS ET DES AMORTISSEMENTS</h1>
        <div class="info">Document généré le {{ date('d/m/Y à H:i') }}</div>
        <div class="info"><strong>Exercice:</strong> {{ date('Y') }}</div>
    </div>

    <!-- Tableau -->
    <table>
        <thead>
            <tr>
                <th rowspan="2">NUMERO DE COMPTE</th>
                <th rowspan="2">DESIGNATION DES IMMOBILISATIONS</th>
                <th rowspan="2">TAUX<br>AMORT. %</th>
                <th rowspan="2">DATE MISE<br>SERVICE</th>
                <th rowspan="2">VALEUR<br>D'ACQUISITION</th>
                <th colspan="3">AMORTISSEMENTS</th>
                <th rowspan="2">VALEUR<br>RESIDUELLE</th>
                <th rowspan="2">Valeurs nette</th>
            </tr>
            <tr>
                <th>ANTERIEURS</th>
                <th>DE L'EXERCICE</th>
                <th>TOTAL</th>
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
                <tr class="category-row">
                    <td colspan="10">
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
                <tr class="subtotal-row">
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
                    <td colspan="10" class="text-center">
                        Aucune immobilisation trouvée
                    </td>
                </tr>
            @endforelse

            @if($articlesData->isNotEmpty())
                <!-- Ligne de total général -->
                <tr class="total-row">
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

    <!-- Pied de page -->
    <div class="print-footer">
        <div><strong>Note:</strong> Ce tableau présente l'état des immobilisations et des amortissements au {{ date('d/m/Y') }}</div>

        <div class="signature-section">
            <div class="signature-box">
                <div>Le Comptable</div>
                <div class="signature-line">Signature et Cachet</div>
            </div>
            <div class="signature-box">
                <div>Le Directeur</div>
                <div class="signature-line">Signature et Cachet</div>
            </div>
        </div>
    </div>

    <script>
        // Imprimer automatiquement après chargement (optionnel)
        // window.onload = function() { setTimeout(function() { window.print(); }, 500); };

        window.onbeforeprint = function() {
            document.body.style.background = 'white';
        };
    </script>
</body>
</html>
