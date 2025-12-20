@extends('layouts.master')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mx-0">
                <h1>Gestion des Clients</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Gestion des Clients</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('gestions_clients.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>&nbsp; Retour à la liste des Clients
            </a>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ajouter un Client</h5>
                        <form action="{{ route('gestions_clients.store') }}" method="POST">
                            @csrf
                            <!-- Type de Client -->
                            <div class="mb-4" style="background: rgb(232, 240, 243); padding: 10px; border-radius: 5px;">
                                <label class="small fw-bold">Type de Client</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_personne" value="personne" required onchange="toggleFields()">
                                        <label class="form-check-label" for="type_personne">
                                            Personne Physique
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_structure" value="structure" required onchange="toggleFields()">
                                        <label class="form-check-label" for="type_structure">
                                            Structure / Entreprise
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Code et Raison Sociale -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="code" class="small">Code Client</label>
                                    <input type="text" class="form-control" id="code" name="code" disabled>
                                </div>
                                <div class="col-md-6 mb-3" id="raison_sociale_field">
                                    <label for="raison_sociale" class="small">Raison Sociale</label>
                                    <input type="text" class="form-control" id="raison_sociale" name="raison_sociale" placeholder="Nom de l'entreprise">
                                </div>
                            </div>

                            <!-- Nom et Email -->
                            <div class="row">
                                <div class="col-md-6 mb-3" id="nom_field">
                                    <label for="nom" class="small">Nom du Client</label>
                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Prénom et Nom">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="small">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="exemple@email.com" required>
                                </div>
                            </div>

                            <!-- Téléphone et Ville -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telephone" class="small">Téléphone</label>
                                    <input type="text" class="form-control" id="telephone" name="telephone" placeholder="+xxx xxx xxx xxx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ville" class="small">Ville</label>
                                    <input type="text" class="form-control" id="ville" name="ville" placeholder="Ville">
                                </div>
                            </div>

                            <!-- Adresse -->
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="adresse" class="small">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Adresse complète">
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i>&nbsp; Enregistrer le Client
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>&nbsp; Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script>
    function toggleFields() {
        const typeRadios = document.querySelectorAll('input[name="type"]');
        const raisonSocialeField = document.getElementById('raison_sociale_field');
        const nomField = document.getElementById('nom_field');
        const raisonSocialeInput = document.getElementById('raison_sociale');
        const nomInput = document.getElementById('nom');

        typeRadios.forEach(radio => {
            if (radio.checked) {
                if (radio.value === 'personne') {
                    // Si personne physique: masquer raison_sociale, afficher nom
                    raisonSocialeField.style.display = 'none';
                    raisonSocialeInput.removeAttribute('required');
                    nomField.style.display = 'block';
                    nomInput.setAttribute('required', 'required');
                } else if (radio.value === 'structure') {
                    // Si structure: afficher raison_sociale, masquer nom
                    raisonSocialeField.style.display = 'block';
                    raisonSocialeInput.setAttribute('required', 'required');
                    nomField.style.display = 'none';
                    nomInput.removeAttribute('required');
                }
            }
        });
    }

    // Initialiser l'affichage au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields();
    });
</script>

