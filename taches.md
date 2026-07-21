# ProjetOperation

## 1 . Conception MCD (table used (and view))
- [ok] Préfixe :(id,prefixe TEXT)
- [ok] Types_Operation :(id,nom TEXT (depot,retrait,transfert))
- [ok] bareme_frais :(id,id_type_operation,montant_minimum,montant_maximum, frais,#id_type_operation REFERENCES Types_Operation(id))
- [ok] Client :(id,telephone)
- [ok] Operation :(id,id_type_operation,id_client,montant,numero_destinateur ,frais_appliqué, date_operation, #id_type_operation REFERENCES Types_Operation(id), #id_client REFERENCES Client(id))
- [ok] view_situation_gain
```sql
CREATE VIEW vue_situation_gains AS
SELECT 
    t.nom AS type_operation,
    COUNT(o.id) AS volume_transactions,
    SUM(o.montant) AS volume_financier,
    SUM(o.frais_applique) AS total_gains
FROM operations o
JOIN types_operation t ON o.id_type_operation = t.id
WHERE t.nom IN ('retrait', 'transfert')
GROUP BY t.nom;
```

## 2 . Version 1

### 2.1 . Coté Operateur [Ok:Hasimanjaka]

- **Tableau de bord statistique & Indicateurs (KPIs)**
    - [ok] [20minute] **Page** : `app/Views/operateur/dashboard.php`
    - [ok] [30minute] **Fonction** : `DashboardAdminController::index()`
    - [ok] [45minute] **Base** : `DashboardAdminModel` (Extraction du nombre de clients, total des opérations, cumul des gains, volume financier et sérilisation des logs pour les 8 dernières transactions)
    - [ok] [60minute] **Integration** : Injection des données formatées en JSON pour alimenter dynamiquement des graphiques Chart.js (Bar chart pour les gains, Doughnut pour la répartition des types d'opération, Line chart pour l'évolution mensuelle) et affichage des KPIs sous forme de cartes Bootstrap

- Situation gain via les différents frais ( retrait et transfert)
    - [ok] [15minute] **Page** : `app/Views/operateur/gains.php` (Tableau de bord de l'administration)
    - [ok] [20minute] **Fonction** : `AdminOperateurController::index()` faisant appel à `GainModel::getSituationGains()`
    - [ok] [25minute] **Base** : Lecture depuis la vue `vue_situation_gains` (sélection et agrégation des frais appliqués sur les retraits et transferts)
    - [ok] [35minute] **Integration** : Cartes récapitulatives Bootstrap (Cards success/info) pour le gain brut global, suivies d'un tableau récapitulatif structuré (Volume de transactions, Volume financier global, Total des frais perçus)

- Situation des comptes clients
    - [ok] [15minute] **Page** : `app/Views/operateur/comptes_clients.php` (Vue liste des comptes)
    - [ok] [20minute] **Fonction** : `AdminOperateurController::clients()` appelant `ClientModel::getStatutComptes()`
    - [ok] [30minute] **Base** : Tables `Client` et `Operation` (Calcul dynamique et algébrique du solde de chaque client via la somme des dépôts moins la somme des retraits et transferts)
    - [ok] [45minute] **Integration** : Tableau responsive Bootstrap listant tous les clients enregistred avec barre de recherche, tri et mise en avant des soldes positifs ou nuls

### 2.2 . Coté Client [Ok:Herimino]

- Login automatique avec le numéro de téléphone (pas d’inscription au préalable)
    - [ok] [2minute] **Page** : `app/Views/client/login.php` (Page d'authentification unique)
    - [ok] [5minute] **Fonction** : `AuthController::login()`
    - [ok] [7minute] **Base** : Contrôle du préfixe saisi via la table `Préfixe`. Si valide, recherche dans  `Client`. Si le compte n'existe pas encore, exécution automatique de `ClientModel::insert()` avant d'ouvrir la session utilisateur
    - [ok] [10minute] **Integration** : Interface épurée et centrée (Mobile-first) avec un formulaire Bootstrap contenant un unique champ `<input type="tel">` et message d'erreur en cas de préfixe invalide

- Opérations
    - voir le solde
        - [ok] [10minute] **Page** : `app/Views/client/dashboard.php` (Accueil de l'espace client)
        - [ok] [15minute] **Fonction** : `ClientController::index()` invoquant `OperationModel::calculateSolde($id_client)`
        - [ok] [10minute] **Base** : Table `Operation` (Somme filtrée sur les opérations du client connecté)
        - [ok] [15minute] **Integration** : Bannière ou badge volumineux mis en relief en haut de page affichant le solde disponible formaté en Ariary (ex: `15 000 Ar`)
    - Dépôt
        - [ok] [10minute] **Page** : `app/Views/client/depot.php` (ou onglet/modal dédié)
        - [ok] [15minute] **Fonction** : `ClientController::storeDepot()`
        - [ok] [15minute] **Base** : Table `Operation` (Insertion d'une ligne avec `id_type_operation` lié au dépôt et `frais_appliqué` = 0)
        - [ok] [20minute] **Integration** : Formulaire à champ unique (Montant) avec alerte Bootstrap de succès dès confirmation (considéré comme automatique)
    - Retrait
        - [ok] [10minute] **Page** : `app/Views/client/retrait.php`
        - [ok] [20minute] **Fonction** : `ClientController::storeRetrait()`
        - [ok] [25minute] **Base** : Tables `Operation` et `bareme_frais` (Vérification et extraction du frais lié à la tranche du montant, et validation stricte de la provision )
        - [ok] [35minute] **Integration** : Formulaire de saisie dynamique avec un script JavaScript (JS) modifiant en temps réel l'affichage des frais et du coût total débité avant soumission
    - Transfert
        - [ok] [10minute] **Page** : `app/Views/client/transfert.php`
        - [ok] [20minute] **Fonction** : `ClientController::storeTransfert()`
        - [ok] [30minute] **Base** : Tables `Operation`, `bareme_frais` et `Préfixe` (Contrôle du préfixe du destinataire, récupération du frais applicable selon le barème de transfert, et validation de la provision suffisante du compte émetteur)
        - [ok] [40minute] **Integration** : Formulaire Bootstrap à double entrée (Numéro de téléphone du destinataire et Montant à envoyer) avec récapitulatif détaillé avant validation
    - Historique des opérations
        - [ok] [15minute] **Page** : `app/Views/client/historique.php` (ou table incluse dans le dashboard)
        - [ok] [20minute] **Fonction** : `ClientController::historique()` exploitant `OperationModel::getHistory($id_client)`
        - [ok] [25minute] **Base** : Tables `Operation` et `Types_Operation` (Sélection triée par ordre chronologique décroissant des transactions où l'utilisateur est l'auteur ou le destinataire)
        - [ok] [35minute] **Integration** : Liste ou tableau Bootstrap avec indicateurs de couleur visuels distinctifs (Vert `text-success` pour les flux entrants comme les dépôts et transferts reçus, Rouge `text-danger` / Sombre pour les flux sortants comme les retraits, transferts émis et frais appliqués)

## 2 . Version 1

### 2.1 . Coté Operateur [Ok:Herimino]
- **Tableau de bord statistique & Indicateurs (KPIs)**
    - [ok] [20minute] **Page** : `app/Views/operateur/dashboard.php`
    - [ok] [30minute] **Fonction** : `DashboardAdminController::index()`
    - [ok] [45minute] **Base** : `DashboardAdminModel` (KPIs & Sérialisation Chart.js)
    - [ok] [60minute] **Integration** : Graphiques Chart.js (Bar, Donut, Line) et cartes fluides
- **Situation gain via les différents frais (retrait et transfert)**
    - [ok] [15minute] **Page** : `app/Views/operateur/gains.php`
    - [ok] [20minute] **Fonction** : `AdminOperateurController::index()`
    - [ok] [25minute] **Base** : Lecture depuis la vue `vue_situation_gains`
    - [ok] [35minute] **Integration** : Cartes récapitulatives Bootstrap
- **Situation des comptes clients**
    - [ok] [15minute] **Page** : `app/Views/operateur/comptes_clients.php`
    - [ok] [20minute] **Fonction** : `AdminOperateurController::clients()`
    - [ok] [30minute] **Base** : Calcul dynamique du solde (Dépôts - Retraits - Transferts)
    - [ok] [45minute] **Integration** : Tableau responsive Bootstrap avec barre de recherche

### 2.2 . Coté Client [Ok:]
- **Login automatique avec le numéro de téléphone**
    - [ok] [2minute] **Page** : `app/Views/client/login.php`
    - [ok] [5minute] **Fonction** : `AuthController::login()`
    - [ok] [7minute] **Base** : Vérification préfixe et inscription automatique à la volée
    - [ok] [10minute] **Integration** : Interface Mobile-first épurée
- **Opérations (Solde, Dépôt, Retrait, Transfert, Historique)**
    - [ok] [10minute] **Solde** : `ClientController::index()` avec mise en valeur grand format
    - [ok] [15minute] **Dépôt** : `ClientController::storeDepot()` sans frais
    - [ok] [35minute] **Retrait** : Calcul dynamique JS des frais selon barème avant validation
    - [ok] [40minute] **Transfert** : Formulaire à double entrée (Destinataire + Montant)
    - [ok] [35minute] **Historique** : Tableau avec codes couleur (Vert/Rouge) pour les flux

---

## 3 . Version 2 (Tag v2) - Livraison 17h10

### 3.1 . Coté Operateur []
- **Configuration des opérateurs tiers et préfixes**
    - [ok] [15minute] **Page** : `app/Views/operateur/config_operateurs.php`
    - [ok] [15minute] **Fonction** : `AdminOperateurController::storeOperateur()` et `storePrefixe()`
    - [ok] [10minute] **Base** : Insertion dans les tables `operateurs` (ex: Telma, Orange) et liaison des préfixes associés (034, 032...)
    - [ok] [15minute] **Integration** : Formulaire de gestion de la commission en % par opérateur tiers
- **Séparation des gains (Opérateur Principal vs Autres)**
    - [ok] [15minute] **Page** : `app/Views/operateur/gains.php` (Mise à jour)
    - [ok] [20minute] **Fonction** : `AdminOperateurController::gainsSynthese()`
    - [ok] [20minute] **Base** : Modification SQL pour séparer les calculs selon le flag `est_principal` de l'opérateur de destination
    - [ ok] [15minute] **Integration** : Refonte de l'interface avec deux blocs distincts : "Gains Réseau Local" et "Commissions Inter-Opérateurs"
- **Situation des montants à envoyer (Compensation / Clearing)**
    - [ok] [15minute] **Page** : `app/Views/operateur/compensation.php`
    - [ok] [15minute] **Fonction** : `AdminOperateurController::compensation()`
    - [ ] [15minute] **Base** : Agrégation des montants nets (`SUM(montant)`) transférés vers chaque opérateur tiers (`est_principal = FALSE`)
    - [ ] [15minute] **Integration** : Tableau récapitulatif des balances financières à reverser à chaque entité externe

### 3.2 . Coté Client [Herimino]
- **Option "Inclure les frais de retrait lors de l'envoi"**
    - [ ] [15minute] **Page** : `app/Views/client/transfert.php` (Mise à jour)
    - [ ] [25minute] **Fonction** : `ClientController::storeTransfert()`
    - [ ] [20minute] **Base** : Logique conditionnelle : Si destination = autre opérateur ➔ frais de retrait d'office à 0. Si même opérateur et case cochée ➔ calcul du frais théorique de retrait, ajout au montant débité de l'émetteur, et flag de l'opération mis à 1
    - [ ] [20minute] **Integration** : Case à cocher Bootstrap "Le destinataire recevra le montant net (frais de retrait à ma charge)" avec recalcul temps réel en JavaScript
- **Envoi multiple divisé (Même opérateur uniquement)**
    - [ ] [20minute] **Page** : `app/Views/client/envoi_multiple.php`
    - [ ] [25minute] **Fonction** : `ClientController::storeEnvoiMultiple()`
    - [ ] [20minute] **Base** : Validation stricte (tous les numéros doivent appartenir à l'opérateur principal). Division du montant global par le nombre de numéros valides, vérification de la provision totale, puis boucle d'insertion d'opérations avec le même `batch_envoi_multiple`
    - [ ] [20minute] **Integration** : Champ `<textarea>` pour saisir les numéros séparés par des virgules ou retours à la ligne. Indicateur dynamique indiquant "Montant par personne : X Ar"

# debugage
- Bareme transfert multiple : [ok] [15minute] **Page** : `app/Views/client/envoi_multiple.php` (Mise à jour) [Mise à jour bareme]
- 