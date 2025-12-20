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
            <a href="{{ route('gestions_entrees.show', $entree->id) }}" class="btn-back">
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
                <div class="header-receipt-no">BON D'ENTRÉE</div>
                <div class="header-receipt-id">#{{ str_pad($entree->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="receipt-date">{{ \Carbon\Carbon::parse($entree->created_at)->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>

        <!-- Informations de l'entrée -->
        <div class="info-section">
            <div class="two-columns">
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">N° Entrée :</span>
                        <span class="info-value"><strong>{{ $entree->numero_entree }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">N° Facture :</span>
                        <span class="info-value">{{ $entree->numero_facture ?? '-' }}</span>
                    </div>
                </div>
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Fournisseur :</span>
                        <span
                            class="info-value"><strong>{{ $entree->fournisseur->raison_sociale ?? $entree->fournisseur->nom }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date d'Entrée :</span>
                        <span class="info-value">{{ $entree->date_entree->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau articles -->
        @if ($entree->details && count($entree->details) > 0)
            <div style="margin-top: 25px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Article</th>
                            <th style="width: 15%; text-align: center;">Quantité</th>
                            <th style="width: 25%; text-align: right;">Prix Unitaire</th>
                            <th style="width: 25%; text-align: right;">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entree->details as $detail)
                            <tr>
                                <td>{{ $detail->article->designation ?? 'N/A' }}</td>
                                <td style="text-align: center;">{{ number_format($detail->stock, 0, ',', ' ') }}</td>
                                <td style="text-align: right;">{{ number_format($detail->prix_achat, 0, ',', ' ') }}
                                    FCFA</td>
                                <td style="text-align: right; font-weight: 600;">
                                    {{ number_format($detail->prix_total, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 600;">Montant Total :</td>
                            <td style="text-align: right; font-weight: 700;">
                                {{ number_format($entree->montant_total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 600;">Nombre d'articles :</td>
                            <td style="text-align: right; font-weight: 700;">
                                {{ $entree->details->count() }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 600;">Quantité totale :</td>
                            <td style="text-align: right; font-weight: 700;">
                                {{ number_format($entree->details->sum('stock'), 0, ',', ' ') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <!-- Section Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <strong>Signature Fournisseur</strong>
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
                Ce bon d'entrée a été généré le {{ now()->format('d/m/Y à H:i:s') }} par le système de gestion
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
