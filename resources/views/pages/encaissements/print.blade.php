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
            <a href="{{ route('gestions_encaissements.show', $encaissement->id) }}" class="btn-back">
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
                <div class="header-receipt-no">REÇU D'ENCAISSEMENT</div>
                <div class="header-receipt-id">#{{ str_pad($encaissement->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="receipt-date">{{ \Carbon\Carbon::parse($encaissement->created_at)->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>

        <!-- Informations de la vente -->
        <div class="info-section">
            <div class="two-columns">
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">N° Vente :</span>
                        <span class="info-value"><strong>{{ $encaissement->vente->numero_vente }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mode Paiement :</span>
                        <span class="badge badge-primary">{{ $encaissement->mode_paiement }}</span>
                    </div>
                </div>
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Nom client :</span>
                        <span
                            class="info-value">{{ $encaissement->vente->client->nom ?? $encaissement->vente->client->raison_sociale }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Chauffeur :</span>
                        <span class="info-value">{{ $encaissement->vente->chauffeur ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class=""></div>

        <!-- Informations de l'encaissement -->
        <div class="info-section">
            <div class="two-columns">
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Montant Total :</span>
                        <span class="info-value">{{ number_format($encaissement->vente->montant_total, 0, ',', ' ') }}
                            F</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Reste à Payer :</span>
                        <span class="info-value">
                            <span
                                class="badge badge-primary">{{ number_format($encaissement->vente->montant_total - $encaissement->montant_encaisse, 0, ',', ' ') }}
                                F</span>
                        </span>
                    </div>
                </div>
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Montant Encaissé :</span>
                        <span
                            class="info-value">{{ number_format($encaissement->montant_encaisse, 0, ',', ' ') }}F</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Pourcentage :</span>
                        <span class="info-value">
                            <span
                                class="badge badge-primary">{{ number_format(($encaissement->montant_encaisse / $encaissement->vente->montant_total) * 100, 0, ',', ' ') }}%</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau articles -->
        @if ($encaissement->vente->details && count($encaissement->vente->details) > 0)
            <div style="margin-top: 25px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Article</th>
                            <th style="width: 15%; text-align: center;">Quantité</th>
                            <th style="width: 20%; text-align: right;">Prix Unitaire</th>
                            <th style="width: 25%; text-align: right;">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($encaissement->vente->details as $detail)
                            <tr>
                                <td>{{ $detail->article->designation ?? 'N/A' }}</td>
                                <td style="text-align: center;">{{ $detail->quantite }}</td>
                                <td style="text-align: right;">{{ number_format($detail->prix_vente, 2, ',', ' ') }}
                                    F</td>
                                <td style="text-align: right; font-weight: 600;">
                                    {{ number_format($detail->prix_total, 0, ',', ' ') }} F</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Section Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <strong>Signature Caissier(e)</strong>
                <div style="margin-top: 20px;">_________________</div>
            </div>
            <div class="signature-box">
                <strong>Signature Client</strong>
                <div style="margin-top: 20px;">_________________</div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>
                Ce reçu d'encaissement a été généré le {{ now()->format('d/m/Y à H:i:s') }} par le système de gestion
                SAFRECO-GSM.
            </p>
            <p style="margin-top: 10px;">
                <strong>Merci de conserver ce reçu à titre de justificatif.</strong>
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
