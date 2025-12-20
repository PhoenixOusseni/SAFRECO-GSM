# 📦 DOCUMENTATION COMPLÈTE - SYSTÈME DE GESTION DE STOCK
## SAFRECO-GSM

---

## 📋 **TABLE DES MATIÈRES**

1. [Vue d'ensemble](#vue-densemble)
2. [Fonctionnalités implémentées](#fonctionnalités-implémentées)
3. [Architecture du système](#architecture-du-système)
4. [Modèles de données](#modèles-de-données)
5. [Contrôleurs](#contrôleurs)
6. [Routes disponibles](#routes-disponibles)
7. [Helpers et utilitaires](#helpers-et-utilitaires)
8. [Flux de données](#flux-de-données)
9. [Utilisation](#utilisation)
10. [Exemples de code](#exemples-de-code)

---

## 🎯 **VUE D'ENSEMBLE**

Le système de gestion de stock SAFRECO-GSM permet de :

- ✅ Gérer les **entrées** d'articles dans les dépôts
- ✅ Gérer les **sorties** d'articles des dépôts
- ✅ **Suivre automatiquement** les stocks en temps réel
- ✅ Générer des **historiques complets** des mouvements
- ✅ Produire des **rapports** détaillés
- ✅ **Alerter** sur les stocks faibles
- ✅ **Valider** toutes les données avant traitement

---

## ⚙️ **FONCTIONNALITÉS IMPLÉMENTÉES**

### 1. **Validation des formulaires (Request Validators)**

| Validator | Fichier | Utilisation |
|-----------|---------|-------------|
| `StoreEntreeRequest` | `app/Http/Requests/StoreEntreeRequest.php` | Validation création d'entrée |
| `UpdateEntreeRequest` | `app/Http/Requests/UpdateEntreeRequest.php` | Validation modification d'entrée |
| `StoreSortieRequest` | `app/Http/Requests/StoreSortieRequest.php` | Validation création de sortie |
| `UpdateSortieRequest` | `app/Http/Requests/UpdateSortieRequest.php` | Validation modification de sortie |

**Règles de validation** :
- Fournisseur/Client : nullable, doit exister
- Date : obligatoire, format date valide
- Articles : tableau obligatoire, au moins 1 article
- Quantités : numériques, > 0
- Prix : numériques, >= 0

### 2. **Historique des mouvements**

**Table** : `mouvements_stock`

Chaque entrée ou sortie est enregistrée avec :
- Article concerné
- Dépôt concerné
- Type de mouvement (entrée/sortie)
- Quantité avant le mouvement
- Quantité après le mouvement
- Prix unitaire
- Numéro du document
- Référence à l'entrée ou sortie

### 3. **Rapports de stock**

7 types de rapports disponibles :

| Rapport | Route | Description |
|---------|-------|-------------|
| Dashboard | `/rapports/dashboard` | Vue d'ensemble avec statistiques |
| État des stocks | `/rapports/etat-stocks` | Liste complète des stocks |
| Alertes stock | `/rapports/alertes-stock` | Articles sous le seuil minimal |
| Historique mouvements | `/rapports/historique-mouvements` | Tous les mouvements avec filtres |
| Stocks par dépôt | `/rapports/stocks-par-depot/{id}` | Vue d'un dépôt spécifique |
| Stocks par article | `/rapports/stocks-par-article/{id}` | Vue d'un article spécifique |
| Valorisation stock | `/rapports/valorisation-stock` | Valeur financière des stocks |

### 4. **Système de notifications**

**Helper** : `App\Helpers\StockHelper`

Méthodes disponibles :
- `getAlertes()` : Récupérer tous les stocks en alerte
- `isEnAlerte($articleId, $depotId)` : Vérifier si un article est en alerte
- `countAlertes()` : Compter le nombre d'alertes
- `getStatsDepot($depotId)` : Statistiques d'un dépôt
- `getStatsArticle($articleId)` : Statistiques d'un article
- `isStockSuffisant($articleId, $depotId, $quantite)` : Vérifier disponibilité
- `getArticlesACommander()` : Liste des articles à réapprovisionner

---

## 🏗️ **ARCHITECTURE DU SYSTÈME**

```
┌─────────────────────────────────────────────────────────────┐
│                        UTILISATEUR                          │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                    CONTRÔLEURS                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   Entree     │  │   Sortie     │  │RapportStock  │     │
│  │ Controller   │  │ Controller   │  │ Controller   │     │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘     │
└─────────┼──────────────────┼──────────────────┼─────────────┘
          │                  │                  │
          ▼                  ▼                  ▼
┌─────────────────────────────────────────────────────────────┐
│                    REQUEST VALIDATORS                        │
│     Validation des données avant traitement                 │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                      MODÈLES                                │
│  ┌────────┐  ┌────────┐  ┌────────┐  ┌────────────────┐   │
│  │ Entree │  │ Sortie │  │ Stock  │  │MouvementStock  │   │
│  └───┬────┘  └───┬────┘  └───┬────┘  └───────┬────────┘   │
│      │           │           │               │             │
│      │           │           │               │             │
│      └───────────┴───────────┴───────────────┘             │
│                          │                                  │
└──────────────────────────┼──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                  BASE DE DONNÉES                            │
│  articles │ depots │ stocks │ entrees │ sorties │          │
│  entrees_details │ sorties_details │ mouvements_stock      │
└─────────────────────────────────────────────────────────────┘
```

---

## 📊 **MODÈLES DE DONNÉES**

### **1. Article**
```php
Champs:
- id
- code
- designation
- reference
- prix_achat
- prix_vente
- seuil
- stock (redondant, utiliser table stocks)

Relations:
- hasMany(Stock)
- hasMany(EntreeDetail)
- hasMany(SortieDetail)
- belongsToMany(Depot) via stocks
```

### **2. Depot**
```php
Champs:
- id
- code (unique)
- designation
- localisation
- responsable
- contact
- description

Relations:
- hasMany(Stock)
- hasMany(EntreeDetail)
- hasMany(SortieDetail)
- belongsToMany(Article) via stocks
```

### **3. Stock**
```php
Champs:
- id
- article_id (FK)
- depot_id (FK)
- quantite_disponible
- quantite_reserve
- quantite_minimale

Relations:
- belongsTo(Article)
- belongsTo(Depot)

Index unique: (article_id, depot_id)

Accesseur:
- quantite_reelle = quantite_disponible - quantite_reserve
```

### **4. Entree**
```php
Champs:
- id
- fournisseur_id (FK, nullable)
- numero_entree (unique, auto-généré)
- numero_facture (nullable)
- date_entree
- montant_total
- observations
- statut (enum: recu, en_attente, rejete)

Relations:
- belongsTo(Fournisseur)
- hasMany(EntreeDetail)
```

### **5. EntreeDetail**
```php
Champs:
- id
- entree_id (FK)
- article_id (FK)
- depot_id (FK)
- stock (quantité)
- prix_achat
- prix_total
- observations

Relations:
- belongsTo(Entree)
- belongsTo(Article)
- belongsTo(Depot)
```

### **6. Sortie**
```php
Champs:
- id
- client_id (FK, nullable)
- numero_sortie (unique, auto-généré)
- numero_facture (nullable)
- date_sortie
- type_sortie (enum: vente, transfert, destruction, inventaire)
- montant_total
- observations
- statut (enum: validee, en_attente, rejetee)

Relations:
- belongsTo(Client)
- hasMany(SortieDetail)
```

### **7. SortieDetail**
```php
Champs:
- id
- sortie_id (FK)
- article_id (FK)
- depot_id (FK)
- quantite
- prix_unitaire
- prix_total
- observations

Relations:
- belongsTo(Sortie)
- belongsTo(Article)
- belongsTo(Depot)
```

### **8. MouvementStock** (NOUVEAU)
```php
Champs:
- id
- article_id (FK)
- depot_id (FK)
- type_mouvement (enum: entree, sortie)
- numero_document
- quantite
- quantite_avant
- quantite_apres
- prix_unitaire
- reference_type (entree ou sortie)
- reference_id
- observations
- created_at
- updated_at

Relations:
- belongsTo(Article)
- belongsTo(Depot)

Scopes:
- entrees()
- sorties()
- forArticle($articleId)
- forDepot($depotId)
- betweenDates($debut, $fin)
```

---

## 🎮 **CONTRÔLEURS**

### **1. EntreeController**

**Méthodes** :
- `index()` : Liste toutes les entrées
- `create()` : Affiche le formulaire de création
- `store(Request)` : Crée une nouvelle entrée + met à jour stock + enregistre historique
- `show($id)` : Affiche les détails d'une entrée
- `edit($id)` : Affiche le formulaire de modification
- `update(Request, $id)` : Modifie une entrée + ajuste stock + enregistre historique
- `destroy($id)` : Supprime une entrée + restaure stock + enregistre historique
- `updateStock(...)` : Méthode privée pour mise à jour stock + historique

**Logique métier** :
```php
SI statut = 'recu' ALORS
    Stock augmenté automatiquement
    Mouvement enregistré dans l'historique
SINON
    Stock non modifié
FIN SI
```

### **2. SortieController**

**Méthodes** :
- `index()` : Liste toutes les sorties
- `create()` : Affiche le formulaire de création
- `store(Request)` : Crée une nouvelle sortie + valide stock + met à jour + enregistre historique
- `show($id)` : Affiche les détails d'une sortie
- `edit($id)` : Affiche le formulaire de modification
- `update(Request, $id)` : Modifie une sortie + valide + ajuste stock + enregistre historique
- `destroy($id)` : Supprime une sortie + restaure stock + enregistre historique
- `getStock(Request)` : API pour récupérer stock disponible
- `updateStock(...)` : Méthode privée pour mise à jour stock + historique

**Logique métier** :
```php
SI statut = 'validee' ALORS
    POUR chaque article:
        SI quantite_disponible < quantite_demandée ALORS
            ERREUR : Stock insuffisant
        SINON
            Stock diminué automatiquement
            Mouvement enregistré dans l'historique
        FIN SI
    FIN POUR
SINON
    Stock non modifié
FIN SI
```

### **3. RapportStockController** (NOUVEAU)

**Méthodes** :
- `dashboard()` : Tableau de bord avec statistiques globales
- `etatStocks()` : État complet de tous les stocks
- `alertesStock()` : Stocks en alerte (quantité <= seuil)
- `historiqueMouvements(Request)` : Historique avec filtres
- `stocksParDepot($depotId)` : Stocks d'un dépôt spécifique
- `stocksParArticle($articleId)` : Stocks d'un article spécifique
- `valorisationStock()` : Calcul de la valeur totale des stocks

---

## 🛣️ **ROUTES DISPONIBLES**

### **Routes des entrées**
```php
GET    /entrees              → index    (Liste)
GET    /entrees/create       → create   (Formulaire création)
POST   /entrees              → store    (Enregistrer)
GET    /entrees/{id}         → show     (Détails)
GET    /entrees/{id}/edit    → edit     (Formulaire modification)
PUT    /entrees/{id}         → update   (Modifier)
DELETE /entrees/{id}         → destroy  (Supprimer)
```

### **Routes des sorties**
```php
GET    /sorties              → index    (Liste)
GET    /sorties/create       → create   (Formulaire création)
POST   /sorties              → store    (Enregistrer)
GET    /sorties/{id}         → show     (Détails)
GET    /sorties/{id}/edit    → edit     (Formulaire modification)
PUT    /sorties/{id}         → update   (Modifier)
DELETE /sorties/{id}         → destroy  (Supprimer)
POST   /sorties/get-stock    → getStock (API: obtenir stock)
```

### **Routes des rapports** (NOUVELLES)
```php
GET /rapports/dashboard                    → Tableau de bord
GET /rapports/etat-stocks                  → État complet des stocks
GET /rapports/alertes-stock                → Alertes stock
GET /rapports/historique-mouvements        → Historique avec filtres
GET /rapports/stocks-par-depot/{id?}       → Stocks d'un dépôt
GET /rapports/stocks-par-article/{id?}     → Stocks d'un article
GET /rapports/valorisation-stock           → Valorisation financière
```

---

## 🔧 **HELPERS ET UTILITAIRES**

### **StockHelper** (`App\Helpers\StockHelper`)

**Utilisation** :

```php
use App\Helpers\StockHelper;

// Obtenir toutes les alertes
$alertes = StockHelper::getAlertes();

// Vérifier si un article est en alerte
if (StockHelper::isEnAlerte($articleId, $depotId)) {
    // Afficher notification
}

// Compter les alertes
$nbAlertes = StockHelper::countAlertes();

// Vérifier stock suffisant avant sortie
if (!StockHelper::isStockSuffisant($articleId, $depotId, $quantite)) {
    return "Stock insuffisant";
}

// Obtenir quantité disponible
$dispo = StockHelper::getQuantiteDisponible($articleId, $depotId);

// Liste des articles à commander
$aCommander = StockHelper::getArticlesACommander();
```

---

## 📈 **FLUX DE DONNÉES**

### **Scénario 1 : Création d'une entrée**

```
1. Utilisateur remplit formulaire entrée
   ↓
2. StoreEntreeRequest valide les données
   ↓
3. EntreeController::store() démarre transaction DB
   ↓
4. Génération numéro d'entrée (ENT-00001)
   ↓
5. Création de l'entrée (table entrees)
   ↓
6. POUR chaque article:
   ├─ Création EntreeDetail
   ├─ SI statut = 'recu':
   │  ├─ Récupération stock actuel (quantite_avant)
   │  ├─ Mise à jour stock (increment quantite_disponible)
   │  ├─ Récupération nouveau stock (quantite_apres)
   │  └─ Enregistrement MouvementStock (historique)
   └─ FIN SI
   ↓
7. Calcul montant_total
   ↓
8. Commit transaction
   ↓
9. Redirection avec message succès
```

### **Scénario 2 : Création d'une sortie**

```
1. Utilisateur remplit formulaire sortie
   ↓
2. StoreSortieRequest valide les données
   ↓
3. SortieController::store() démarre transaction DB
   ↓
4. Génération numéro de sortie (SRT-00001)
   ↓
5. SI statut = 'validee':
   ├─ POUR chaque article:
   │  ├─ Récupération stock actuel
   │  ├─ SI quantite_disponible < quantite_demandée:
   │  │  └─ ERREUR : Stock insuffisant (rollback)
   │  └─ FIN SI
   └─ FIN POUR
   ↓
6. Création de la sortie (table sorties)
   ↓
7. POUR chaque article:
   ├─ Création SortieDetail
   ├─ SI statut = 'validee':
   │  ├─ Récupération stock actuel (quantite_avant)
   │  ├─ Mise à jour stock (decrement quantite_disponible)
   │  ├─ Récupération nouveau stock (quantite_apres)
   │  └─ Enregistrement MouvementStock (historique)
   └─ FIN SI
   ↓
8. Calcul montant_total
   ↓
9. Commit transaction
   ↓
10. Redirection avec message succès
```

---

## 💼 **UTILISATION**

### **Créer une entrée**

1. Accéder à `/entrees/create`
2. Sélectionner un fournisseur (optionnel)
3. Renseigner la date d'entrée
4. Ajouter les articles avec :
   - Article
   - Dépôt
   - Quantité
   - Prix d'achat
5. Choisir le statut (recu par défaut)
6. Soumettre le formulaire

**Résultat** :
- Entrée créée avec numéro auto-généré
- Stock mis à jour automatiquement si statut = "recu"
- Mouvement enregistré dans l'historique

### **Créer une sortie**

1. Accéder à `/sorties/create`
2. Sélectionner un client (optionnel)
3. Renseigner la date de sortie
4. Choisir le type (vente, transfert, destruction, inventaire)
5. Ajouter les articles avec :
   - Article
   - Dépôt
   - Quantité
   - Prix unitaire
6. Choisir le statut (validee par défaut)
7. Soumettre le formulaire

**Résultat** :
- Validation du stock disponible
- Sortie créée avec numéro auto-généré
- Stock mis à jour automatiquement si statut = "validee"
- Mouvement enregistré dans l'historique

### **Consulter les rapports**

1. Accéder à `/rapports/dashboard` pour vue d'ensemble
2. Consulter les différents rapports selon les besoins :
   - `/rapports/alertes-stock` → Articles à réapprovisionner
   - `/rapports/historique-mouvements` → Traçabilité complète
   - `/rapports/valorisation-stock` → Valeur financière

---

## 💻 **EXEMPLES DE CODE**

### **Utiliser le helper dans un contrôleur**

```php
use App\Helpers\StockHelper;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupérer le nombre d'alertes
        $nbAlertes = StockHelper::countAlertes();

        // Récupérer la liste des alertes
        $alertes = StockHelper::getAlertes();

        return view('dashboard', compact('nbAlertes', 'alertes'));
    }
}
```

### **Vérifier stock avant sortie (Vue)**

```blade
@if(StockHelper::isEnAlerte($article->id, $depot->id))
    <div class="alert alert-warning">
        ⚠️ Stock faible pour cet article
    </div>
@endif

Disponible : {{ StockHelper::getQuantiteDisponible($article->id, $depot->id) }}
```

### **Afficher les mouvements d'un article**

```php
use App\Models\MouvementStock;

$mouvements = MouvementStock::forArticle($articleId)
    ->forDepot($depotId)
    ->betweenDates('2025-01-01', '2025-12-31')
    ->get();

foreach ($mouvements as $mouvement) {
    echo "{$mouvement->type_mouvement}: {$mouvement->quantite} ";
    echo "({$mouvement->quantite_avant} → {$mouvement->quantite_apres})";
}
```

---

## 📌 **POINTS IMPORTANTS**

1. **Transactions DB** : Toutes les opérations critiques sont encapsulées dans des transactions pour garantir l'intégrité

2. **Historique complet** : Chaque modification de stock est tracée avec quantité avant/après

3. **Validation stricte** : Les Request Validators empêchent les données invalides

4. **Protection contre stocks négatifs** : Impossible de créer une sortie si stock insuffisant

5. **Numérotation automatique** : Les documents sont numérotés séquentiellement (ENT-00001, SRT-00001)

6. **Statuts flexibles** : Permet de créer des documents "en attente" sans impacter le stock immédiatement

---

## 🚀 **PROCHAINES ÉVOLUTIONS POSSIBLES**

- [ ] Interface web pour les vues (actuellement seulement le backend)
- [ ] Export des rapports en PDF/Excel
- [ ] Notifications par email pour alertes stock
- [ ] Gestion des réservations de stock
- [ ] Inventaire physique avec ajustements
- [ ] Traçabilité par lots (pour articles périssables)
- [ ] Statistiques graphiques (charts)

---

## 📞 **SUPPORT**

Pour toute question ou problème :
- Consulter cette documentation
- Vérifier les logs Laravel : `storage/logs/laravel.log`
- Utiliser `php artisan route:list` pour voir toutes les routes

---

**Version** : 1.0
**Date** : 16 Décembre 2025
**Projet** : SAFRECO-GSM
