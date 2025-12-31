<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link " href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-graph-up"></i><span>ETAT DES VENTES</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('gestions_ventes.index') }}">
                        <i class="bi bi-circle"></i><span>Journal des ventes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gestions_achats.index') }}">
                        <i class="bi bi-circle"></i><span>Journal des achats</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-box-seam"></i><span>STOCK</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('gestions_entrees.index') }}">
                        <i class="bi bi-circle"></i><span>Entrees</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gestions_sorties.index') }}">
                        <i class="bi bi-circle"></i><span>Sorties</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gestions_transferts.index') }}">
                        <i class="bi bi-circle"></i><span>Transferts</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gestions_inventaires.index') }}">
                        <i class="bi bi-circle"></i><span>Inventaire</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Forms Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-cash-coin"></i><span>TRESORERIE</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('gestions_encaissements.index') }}">
                        <i class="bi bi-circle"></i><span>Encaissements</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gestions_decaissements.index') }}">
                        <i class="bi bi-circle"></i><span>Decaissements</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Garantie bancaire</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#code-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-barcode"></i><span>CODE BARRE</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="code-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('articles.article_code_barre') }}">
                        <i class="bi bi-pencil-square"></i><span>Editions</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav -->

        <li class="nav-heading">CONFIGURATION</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('entetes.index') }}">
                <i class="bi bi-building"></i>
                <span>Infos société</span>
            </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('gestions_clients.index') }}">
                <i class="bi bi-people"></i>
                <span>Clients</span>
            </a>
        </li><!-- End F.A.Q Page Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('gestions_fournisseurs.index') }}">
                <i class="bi bi-truck"></i>
                <span>Fournisseurs</span>
            </a>
        </li><!-- End Contact Page Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('gestions_articles.index') }}">
                <i class="bi bi-bag"></i>
                <span>Articles</span>
            </a>
        </li><!-- End Login Page Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('gestions_depots.index') }}">
                <i class="bi bi-diagram-3"></i>
                <span>Depots</span>
            </a>
        </li><!-- End Error 404 Page Nav -->
        {{-- <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('amortissements.taux_amortissement') }}">
                <i class="bi bi-percent"></i>
                <span>Tableau d'amortissements</span>
            </a>
        </li><!-- End Depreciation Rate Nav --> --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('gestions_banques.index') }}">
                <i class="bi bi-bank"></i>
                <span>Banques</span>
            </a>
        </li><!-- End Blank Page Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('gestions_caisses.index') }}">
                <i class="bi bi-safe"></i>
                <span>Caisse</span>
            </a>
        </li><!-- End Blank Page Nav -->
        <li>
            <a class="nav-link collapsed" href="{{ route('gestions_utilisateurs.index') }}">
                <i class="bi bi-person-circle"></i>
                <span>Utilisateurs</span>
            </a>
        </li>
    </ul>
</aside><!-- End Sidebar-->
