@extends('layouts.master')

@section('content')
<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Modifier Inventaire</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('gestions_inventaires.index') }}">Inventaires</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($inventaire->statut == 'en_cours')
                <form action="{{ route('gestions_inventaires.valider', $inventaire->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Valider
                    </button>
                </form>
            @endif
            <a href="{{ route('gestions_inventaires.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inventaire {{ $inventaire->numero_inventaire }}</h5>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('gestions_inventaires.update', $inventaire->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date_inventaire"
                                    value="{{ $inventaire->date_inventaire->format('Y-m-d') }}"
                                    {{ $inventaire->statut == 'valide' ? 'readonly' : '' }}>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Dépôt</label>
                                <input type="text" class="form-control" value="{{ $inventaire->depot->designation ?? 'N/A' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Observation</label>
                                <input type="text" class="form-control" name="observation"
                                    value="{{ $inventaire->observation }}"
                                    {{ $inventaire->statut == 'valide' ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th class="text-center">Qté Théorique</th>
                                    <th class="text-center">Qté Physique</th>
                                    <th class="text-center">Écart</th>
                                    <th>Observation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventaire->details as $detail)
                                <tr data-theorique="{{ $detail->quantite_theorique }}">
                                    <td>{{ $detail->article->designation ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $detail->quantite_theorique }}</td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm text-center quantite-physique"
                                            name="details[{{ $detail->id }}][quantite_physique]"
                                            value="{{ $detail->quantite_physique }}" min="0"
                                            {{ $inventaire->statut == 'valide' ? 'readonly' : '' }}
                                            data-detail-id="{{ $detail->id }}">
                                    </td>
                                    <td class="text-center">
                                        <span class="badge ecart-badge bg-{{ $detail->ecart_quantite == 0 ? 'secondary' : ($detail->ecart_quantite > 0 ? 'success' : 'danger') }}">
                                            {{ $detail->ecart_quantite > 0 ? '+' : '' }}{{ $detail->ecart_quantite }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                            name="details[{{ $detail->id }}][observation]"
                                            value="{{ $detail->observation }}"
                                            {{ $inventaire->statut == 'valide' ? 'readonly' : '' }}>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th class="text-end">Totaux:</th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center">
                                        <span class="badge bg-{{ $inventaire->ecart_total_quantite == 0 ? 'secondary' : ($inventaire->ecart_total_quantite > 0 ? 'success' : 'danger') }}">
                                            {{ $inventaire->ecart_total_quantite > 0 ? '+' : '' }}{{ $inventaire->ecart_total_quantite }}
                                        </span>
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>

                        @if($inventaire->statut != 'valide')
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i>&nbsp; Enregistrer
                        </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mettre à jour l'écart dynamiquement quand la quantité physique change
    const quantiteInputs = document.querySelectorAll('.quantite-physique');

    quantiteInputs.forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            const quantiteTheorique = parseInt(row.dataset.theorique) || 0;
            const quantitePhysique = parseInt(this.value) || 0;
            const ecart = quantitePhysique - quantiteTheorique;

            const ecartBadge = row.querySelector('.ecart-badge');

            // Mettre à jour la valeur
            ecartBadge.textContent = (ecart > 0 ? '+' : '') + ecart;

            // Mettre à jour la couleur
            ecartBadge.className = 'badge ecart-badge bg-' +
                (ecart === 0 ? 'secondary' : (ecart > 0 ? 'success' : 'danger'));
        });
    });

    // Calculer le total des écarts
    function updateTotalEcart() {
        let totalEcart = 0;
        quantiteInputs.forEach(input => {
            const row = input.closest('tr');
            const quantiteTheorique = parseInt(row.dataset.theorique) || 0;
            const quantitePhysique = parseInt(input.value) || 0;
            totalEcart += (quantitePhysique - quantiteTheorique);
        });

        const totalBadge = document.querySelector('tfoot .badge');
        if (totalBadge) {
            totalBadge.textContent = (totalEcart > 0 ? '+' : '') + totalEcart;
            totalBadge.className = 'badge bg-' +
                (totalEcart === 0 ? 'secondary' : (totalEcart > 0 ? 'success' : 'danger'));
        }
    }

    // Mettre à jour le total à chaque changement
    quantiteInputs.forEach(input => {
        input.addEventListener('input', updateTotalEcart);
    });
});
</script>
@endsection
