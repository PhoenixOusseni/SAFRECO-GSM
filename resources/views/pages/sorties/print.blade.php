<!DOCTYPE html>
<html lang="fr">

<head>
    @include('pages.encaissements.style')
</head>

<body>
    <div class="print-container">
        <!-- Boutons d'impression (cachés à l'impression) -->
        <div class="print-buttons no-print">
            <button class="btn-print" onclick="window.print()">
                <i class="bi bi-printer"></i> Imprimer
            </button>
            <a href="{{ route('gestions_sorties.show', $sortie->id) }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        @php
            $entete = \App\Models\Entete::first();
        @endphp

        <!-- En-tête personnalisé avec infos de la table entete -->
        <div class="header">
            @if ($entete && $entete->logo)
                <div class="header-logo">
                    <img src="{{ asset('storage/' . $entete->logo) }}" alt="Logo">
                </div>
            @endif

            <div class="header-info">
                <div class="header-title">{{ $entete->titre ?? 'SAFRECO-GSM' }}</div>
                @if ($entete && $entete->sous_titre)
                    <div class="header-subtitle">{{ $entete->sous_titre }}</div>
                @endif
                <div class="header-contact">
                    @if ($entete && $entete->telephone)
                        <div class="header-contact-item">
                            <span>☎ {{ $entete->telephone }}</span>
                        </div>
                    @endif
                    @if ($entete && $entete->email)
                        <div class="header-contact-item">
                            <span>✉ {{ $entete->email }}</span>
                        </div>
                    @endif
                    @if ($entete && $entete->adresse)
                        <div class="header-contact-item">
                            <span>📍 {{ $entete->adresse }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="header-receipt">
                <div class="header-receipt-no">BON DE SORTIE</div>
                <div class="header-receipt-id">#{{ str_pad($sortie->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="receipt-date">{{ \Carbon\Carbon::parse($sortie->created_at)->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>

        <!-- Informations de la sortie -->
        <div class="info-section">
            <div class="two-columns">
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">N° Sortie :</span>
                        <span class="info-value"><strong>{{ $sortie->numero_sortie }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date Sortie :</span>
                        <span class="info-value">{{ $sortie->date_sortie->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Client :</span>
                        <span class="info-value"><strong>{{ $sortie->client->nom ?? $sortie->client->raison_sociale }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">N° Facture :</span>
                        <span class="info-value">{{ $sortie->numero_facture ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau articles -->
        @if ($sortie->details && count($sortie->details) > 0)
            <div style="margin-top: 25px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Article</th>
                            <th style="width: 15%;">Dépôt</th>
                            <th style="width: 15%; text-align: center;">Quantité</th>
                            <th style="width: 20%; text-align: right;">Prix Unitaire</th>
                            <th style="width: 20%; text-align: right;">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sortie->details as $detail)
                            <tr>
                                <td>{{ $detail->article->designation ?? 'N/A' }}</td>
                                <td>{{ $detail->depot->designation ?? 'N/A' }}</td>
                                <td style="text-align: center;">{{ number_format($detail->quantite, 0, ',', ' ') }}
                                </td>
                                <td style="text-align: right;">{{ number_format($detail->prix_vente, 0, ',', ' ') }}
                                    FCFA</td>
                                <td style="text-align: right; font-weight: 600;">
                                    {{ number_format($detail->prix_total, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right; font-weight: 600;">Montant Total :</td>
                            <td style="text-align: right; font-weight: 700;">
                                {{ number_format($sortie->montant_total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right; font-weight: 600;">Nombre d'articles :</td>
                            <td style="text-align: right; font-weight: 700;">
                                {{ $sortie->details->count() }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right; font-weight: 600;">Quantité totale :</td>
                            <td style="text-align: right; font-weight: 700;">
                                {{ number_format($sortie->details->sum('quantite'), 0, ',', ' ') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <!-- Section Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <strong>Signature Responsable</strong>
                <div style="margin-top: 20px;">_________________</div>
            </div>
            <div class="signature-box">
                <strong>Signature Réceptionnaire</strong>
                <div style="margin-top: 20px;">_________________</div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>
                Ce bon de sortie a été généré le {{ now()->format('d/m/Y à H:i:s') }} par le système de gestion
                SAFRECO-GSM.
            </p>
            <p style="margin-top: 10px;">
                <strong>Merci de conserver ce bon à titre de justificatif.</strong>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash === '#print') {
                window.print();
            }
        });
    </script>
</body>

</html>
