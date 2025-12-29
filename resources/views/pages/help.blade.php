@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <h1><i class="bi bi-question-circle"></i> Centre d'Aide</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                <li class="breadcrumb-item active">Aide</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Guide de Démarrage -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-rocket-takeoff text-primary"></i> Guide de Démarrage Rapide</h5>

                        <div class="accordion accordion-flush" id="accordionGettingStarted">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                        <i class="bi bi-1-circle me-2"></i> Comment créer un nouveau fournisseur ?
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#accordionGettingStarted">
                                    <div class="accordion-body">
                                        <ol>
                                            <li>Accédez au menu <strong>Fournisseurs</strong> dans la barre latérale</li>
                                            <li>Cliquez sur le bouton <span class="badge bg-primary">+ Ajouter un Fournisseur</span></li>
                                            <li>Remplissez les informations requises (Type, Nom/Raison Sociale, Email, Téléphone, etc.)</li>
                                            <li>Cliquez sur <span class="badge bg-success">Enregistrer</span></li>
                                        </ol>
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-lightbulb"></i> <strong>Astuce:</strong> Vous pouvez également importer plusieurs fournisseurs en utilisant un fichier CSV.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                        <i class="bi bi-2-circle me-2"></i> Comment enregistrer une entrée de stock ?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#accordionGettingStarted">
                                    <div class="accordion-body">
                                        <ol>
                                            <li>Allez dans <strong>Entrées de Stock</strong></li>
                                            <li>Cliquez sur <span class="badge bg-primary">+ Nouvelle Entrée</span></li>
                                            <li>Sélectionnez le fournisseur, la date et le numéro de facture</li>
                                            <li>Ajoutez les articles avec leurs quantités et prix d'achat</li>
                                            <li>Sélectionnez le dépôt pour chaque article</li>
                                            <li>Validez l'entrée avec le statut <span class="badge bg-success">Reçu</span></li>
                                        </ol>
                                        <div class="alert alert-warning mb-0">
                                            <i class="bi bi-exclamation-triangle"></i> <strong>Important:</strong> Le stock n'est mis à jour que si le statut est "Reçu".
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                        <i class="bi bi-3-circle me-2"></i> Comment effectuer une vente ?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#accordionGettingStarted">
                                    <div class="accordion-body">
                                        <ol>
                                            <li>Accédez au module <strong>Ventes</strong></li>
                                            <li>Cliquez sur <span class="badge bg-primary">+ Nouvelle Vente</span></li>
                                            <li>Sélectionnez le client et la date de vente</li>
                                            <li>Ajoutez les articles à vendre (le prix se remplit automatiquement)</li>
                                            <li>Vérifiez le stock disponible avant validation</li>
                                            <li>Enregistrez la vente</li>
                                        </ol>
                                        <div class="alert alert-success mb-0">
                                            <i class="bi bi-check-circle"></i> <strong>Note:</strong> Le stock est automatiquement déduit après la vente.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                        <i class="bi bi-4-circle me-2"></i> Comment transférer des articles entre dépôts ?
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionGettingStarted">
                                    <div class="accordion-body">
                                        <ol>
                                            <li>Allez dans <strong>Transferts</strong></li>
                                            <li>Cliquez sur <span class="badge bg-primary">+ Nouveau Transfert</span></li>
                                            <li>Sélectionnez le dépôt source et le dépôt destination</li>
                                            <li>Choisissez l'article et la quantité à transférer</li>
                                            <li>Vérifiez le stock disponible dans le dépôt source</li>
                                            <li>Validez le transfert</li>
                                        </ol>
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle"></i> <strong>Info:</strong> Le transfert met à jour simultanément les deux dépôts.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fonctionnalités Principales -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-list-check text-success"></i> Fonctionnalités Principales</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="feature-box p-3 border rounded">
                                    <h6><i class="bi bi-box-seam text-primary"></i> Gestion des Articles</h6>
                                    <p class="small mb-0">Créez, modifiez et supprimez vos articles. Gérez les prix, codes et références.</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box p-3 border rounded">
                                    <h6><i class="bi bi-building text-info"></i> Gestion des Dépôts</h6>
                                    <p class="small mb-0">Organisez vos stocks dans différents dépôts et localisations.</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box p-3 border rounded">
                                    <h6><i class="bi bi-arrow-down-circle text-success"></i> Entrées de Stock</h6>
                                    <p class="small mb-0">Enregistrez les réceptions de marchandises et mettez à jour automatiquement vos stocks.</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box p-3 border rounded">
                                    <h6><i class="bi bi-arrow-up-circle text-warning"></i> Sorties de Stock</h6>
                                    <p class="small mb-0">Gérez les sorties de stock vers vos clients avec traçabilité complète.</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box p-3 border rounded">
                                    <h6><i class="bi bi-cart-check text-primary"></i> Gestion des Ventes</h6>
                                    <p class="small mb-0">Enregistrez vos ventes avec déduction automatique du stock.</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box p-3 border rounded">
                                    <h6><i class="bi bi-arrow-left-right text-info"></i> Transferts Inter-dépôts</h6>
                                    <p class="small mb-0">Déplacez vos articles entre différents dépôts facilement.</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box p-3 border rounded">
                                    <h6><i class="bi bi-cash-coin text-success"></i> Encaissements</h6>
                                    <p class="small mb-0">Gérez les paiements clients et suivez les soldes.</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box p-3 border rounded">
                                    <h6><i class="bi bi-wallet2 text-warning"></i> Décaissements</h6>
                                    <p class="small mb-0">Enregistrez les paiements fournisseurs et suivez vos dépenses.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-question-circle text-warning"></i> Questions Fréquentes (FAQ)</h5>

                        <div class="accordion" id="accordionFAQ">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Comment importer des données en masse ?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Plusieurs modules supportent l'import CSV:
                                        <ul>
                                            <li><strong>Fournisseurs:</strong> Utilisez le bouton "Importer CSV" et téléchargez d'abord le template</li>
                                            <li><strong>Entrées:</strong> Importez directement vos bons d'entrée avec articles</li>
                                        </ul>
                                        Assurez-vous que votre fichier CSV respecte le format du template téléchargeable.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        Que faire en cas de stock négatif ?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Un stock négatif indique généralement une erreur de saisie. Vérifiez:
                                        <ul>
                                            <li>Les quantités dans les sorties et ventes</li>
                                            <li>Les entrées de stock correspondantes</li>
                                            <li>L'historique des mouvements dans le module Stocks</li>
                                        </ul>
                                        Ajustez ensuite via une entrée de régularisation.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Comment modifier mon mot de passe ?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        <ol>
                                            <li>Cliquez sur votre avatar en haut à droite</li>
                                            <li>Sélectionnez "Mon Profil"</li>
                                            <li>Allez dans l'onglet "Changer le Mot de Passe"</li>
                                            <li>Entrez votre mot de passe actuel et le nouveau</li>
                                            <li>Confirmez le changement</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                        Comment consulter l'historique des mouvements de stock ?
                                    </button>
                                </h2>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Accédez au module <strong>Stocks</strong> puis consultez les onglets:
                                        <ul>
                                            <li><strong>Stock actuel:</strong> Vue d'ensemble des quantités disponibles</li>
                                            <li><strong>Mouvements:</strong> Historique détaillé de tous les mouvements</li>
                                            <li><strong>Alertes:</strong> Articles avec stock faible</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Raccourcis Clavier -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-keyboard text-info"></i> Raccourcis Clavier</h5>

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Raccourci</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><kbd>Ctrl</kbd> + <kbd>S</kbd></td>
                                        <td>Enregistrer le formulaire actuel</td>
                                    </tr>
                                    <tr>
                                        <td><kbd>Ctrl</kbd> + <kbd>N</kbd></td>
                                        <td>Créer un nouvel élément</td>
                                    </tr>
                                    <tr>
                                        <td><kbd>Esc</kbd></td>
                                        <td>Fermer les modals</td>
                                    </tr>
                                    <tr>
                                        <td><kbd>Tab</kbd></td>
                                        <td>Navigation entre les champs</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <!-- Contact Support -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-headset text-primary"></i> Besoin d'Aide ?</h5>
                        <p class="small">Notre équipe est là pour vous aider.</p>

                        <div class="d-grid gap-2">
                            <a href="mailto:support@safreco.com" class="btn btn-primary btn-sm">
                                <i class="bi bi-envelope"></i> Contacter le Support
                            </a>
                            <a href="tel:+22500000000" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-telephone"></i> Appeler: +225 00 00 00 00
                            </a>
                        </div>

                        <hr>

                        <h6 class="fw-bold">Heures d'ouverture</h6>
                        <p class="small mb-1"><i class="bi bi-clock"></i> Lun - Ven: 8h - 17h</p>
                        <p class="small"><i class="bi bi-clock"></i> Sam: 9h - 13h</p>
                    </div>
                </div>

                <!-- Ressources Utiles -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-book text-success"></i> Ressources</h5>

                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-file-pdf text-danger"></i> Guide Utilisateur (PDF)
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-play-circle text-primary"></i> Tutoriels Vidéo
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-journal-text text-info"></i> Documentation Technique
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-calendar-event text-warning"></i> Planning des Formations
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mises à Jour -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-megaphone text-warning"></i> Nouveautés</h5>

                        <div class="timeline">
                            <div class="timeline-item mb-3">
                                <h6 class="fw-bold mb-1">Version 1.2.0</h6>
                                <p class="small text-muted mb-1">29 Décembre 2025</p>
                                <ul class="small mb-0">
                                    <li>Import CSV pour les fournisseurs</li>
                                    <li>Import CSV pour les entrées</li>
                                    <li>Amélioration des notifications</li>
                                </ul>
                            </div>

                            <div class="timeline-item mb-3">
                                <h6 class="fw-bold mb-1">Version 1.1.0</h6>
                                <p class="small text-muted mb-1">15 Décembre 2025</p>
                                <ul class="small mb-0">
                                    <li>Module de transferts inter-dépôts</li>
                                    <li>Système de Select2 pour recherche</li>
                                    <li>Optimisation des performances</li>
                                </ul>
                            </div>

                            <div class="timeline-item">
                                <h6 class="fw-bold mb-1">Version 1.0.0</h6>
                                <p class="small text-muted mb-1">1 Décembre 2025</p>
                                <ul class="small mb-0">
                                    <li>Lancement initial</li>
                                    <li>Modules de base opérationnels</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Auto-ouvrir la première section si pas de hash dans l'URL
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.location.hash) {
            const firstAccordion = document.querySelector('#accordionGettingStarted .accordion-collapse');
            if (firstAccordion) {
                firstAccordion.classList.add('show');
                const firstButton = document.querySelector('#accordionGettingStarted .accordion-button');
                if (firstButton) {
                    firstButton.classList.remove('collapsed');
                }
            }
        }
    });
</script>
@endsection
