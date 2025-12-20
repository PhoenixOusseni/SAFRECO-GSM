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
            <a href="{{ route('gestions_transferts.show', $transfert->id) }}" class="btn-back">
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
                <div class="header-receipt-no">BON DE TRANSFERT</div>
                <div class="header-receipt-id">#{{ str_pad($transfert->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="receipt-date">{{ \Carbon\Carbon::parse($transfert->created_at)->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>

        <!-- Informations du Transfert -->
        <div class="info-section">
            <div class="two-columns">
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Code Transfert :</span>
                        <span class="info-value"><strong>{{ $transfert->code }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date Transfert :</span>
                        <span class="info-value">{{ $transfert->date_transfert->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Article :</span>
                        <span class="info-value"><strong>{{ $transfert->article->designation }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Quantité transfrere :</span>
                        <span class="info-value"><strong>{{ number_format($transfert->quantite, 2, ',', ' ') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Informations des Dépôts -->
        <div class="info-section">
            <div class="info-section-title">Dépôt Source</div>
            <div class="two-columns">
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Code :</span>
                        <span class="info-value"><strong>{{ $transfert->depotSource->code }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Désignation :</span>
                        <span class="info-value">{{ $transfert->depotSource->designation }}</span>
                    </div>
                </div>
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Localisation :</span>
                        <span class="info-value">{{ $transfert->depotSource->localisation ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Responsable :</span>
                        <span class="info-value">{{ $transfert->depotSource->responsable ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-section-title">Dépôt Destination</div>
            <div class="two-columns">
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Code :</span>
                        <span class="info-value"><strong>{{ $transfert->depotDestination->code }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Désignation :</span>
                        <span class="info-value">{{ $transfert->depotDestination->designation }}</span>
                    </div>
                </div>
                <div class="column">
                    <div class="info-row">
                        <span class="info-label">Localisation :</span>
                        <span class="info-value">{{ $transfert->depotDestination->localisation ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Responsable :</span>
                        <span class="info-value">{{ $transfert->depotDestination->responsable ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de Transport -->
        @if($transfert->numero_vehicule || $transfert->nom_chauffeur)
            <div class="info-section">
                <div class="info-section-title">Informations de Transport</div>
                <div class="two-columns">
                    <div class="column">
                        <div class="info-row">
                            <span class="info-label">Numéro Véhicule :</span>
                            <span class="info-value">{{ $transfert->numero_vehicule ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="column">
                        <div class="info-row">
                            <span class="info-label">Nom Chauffeur :</span>
                            <span class="info-value">{{ $transfert->nom_chauffeur ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>
        @endif

        <!-- Section Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <strong>Signature Responsable Source</strong>
                <div style="margin-top: 20px;">_________________</div>
            </div>
            <div class="signature-box">
                <strong>Signature Responsable Destination</strong>
                <div style="margin-top: 20px;">_________________</div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>
                Ce bon de transfert a été généré le {{ now()->format('d/m/Y à H:i:s') }} par le système de gestion
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
