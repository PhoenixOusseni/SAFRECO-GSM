<!-- Modal Ajouter Banque -->
<div class="modal fade" id="addBanqueModal" tabindex="-1" aria-labelledby="addBanqueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBanqueModalLabel">
                    <i class="bi bi-plus-circle"></i> Ajouter une Banque
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('gestions_banques.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_compte" class="form-label small">Numéro de Compte <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="numero_compte" name="numero_compte"
                                placeholder="Numéro de compte de la banque" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="solde" class="form-label small">Solde</label>
                            <input type="number" step="0.01" class="form-control" id="solde" name="solde"
                                placeholder="Solde de la banque" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label small">Dénomination <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="designation" name="designation"
                                placeholder="Dénomination de la banque" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label small">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone"
                                placeholder="Numéro de téléphone">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label small">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Adresse email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="adresse" class="form-label small">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse"
                                placeholder="Adresse complète">
                        </div>
                    </div>
                </div>
                <div class="mx-3 mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal Ajouter Caisse -->
<div class="modal fade" id="addCaisseModal" tabindex="-1" aria-labelledby="addCaisseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCaisseModalLabel">
                    <i class="bi bi-plus-circle"></i> Ajouter une Caisse
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('gestions_caisses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_compte" class="form-label small">Numéro de Compte <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="numero_compte" name="numero_compte"
                                placeholder="Numéro de compte de la banque" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="solde" class="form-label small">Solde</label>
                            <input type="number" step="0.01" class="form-control" id="solde" name="solde"
                                placeholder="Solde de la banque" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label small">Dénomination <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="designation" name="designation"
                                placeholder="Dénomination de la banque" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label small">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone"
                                placeholder="Numéro de téléphone">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label small">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Adresse email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="adresse" class="form-label small">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse"
                                placeholder="Adresse complète">
                        </div>
                    </div>
                </div>
                <div class="mx-3 mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
