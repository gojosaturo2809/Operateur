# ProjetOperation
## 1 . Conception MCD (table used (and view))
- Préfixe :(id,prefixe TEXT)
- Types_Operation :(id,nom TEXT (depot,retrait,transfert))
- bareme_frais :(id,id_type_operation,montant_minimum,montant_maximum, frais,#id_type_operation REFERENCES Types_Operation(id))
- Client :(id,telephone)
- Operation :(id,id_type_operation,id_client,montant,numero_destinateur ,frais_appliqué, date_operation, #id_type_operation REFERENCES Types_Operation(id), #id_client REFERENCES Client(id))
- view_situation_gain
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
### 2.1 . Coté Operateur
- Situation gain via les différents frais ( retrait et transfert)
    - **Page** : `app/Views/operator/gains.php` (Tableau de bord de l'administration)
    - **Fonction** : `OperatorController::index()` faisant appel à `GainModel::getSituationGains()`
    - **Base** : Lecture depuis la vue `vue_situation_gains` (sélection et agrégation des frais appliqués sur les retraits et transferts)
    - **Integration** : Cartes récapitulatives Bootstrap (Cards success/info) pour le gain brut global, suivies d'un tableau récapitulatif structuré (Volume de transactions, Volume financier global, Total des frais perçus)

- Situation des comptes clients
    - **Page** : `app/Views/operator/comptes_clients.php` (Vue liste des comptes)
    - **Fonction** : `OperatorController::clients()` appelant `ClientModel::getStatutComptes()`
    - **Base** : Tables `Client` et `Operation` (Calcul dynamique et algébrique du solde de chaque client via la somme des dépôts moins la somme des retraits et transferts)
    - **Integration** : Tableau responsive Bootstrap listant tous les clients enregistred avec barre de recherche, tri et mise en avant des soldes positifs ou nuls

### 2.2 . Coté Client [Ok:Herimino]
- Login automatique avec le numéro de téléphone (pas d’inscription au préalable)
    - [ok] [2minute] **Page** : `app/Views/client/login.php` (Page d'authentification unique) 

    - [ok] [5minute] **Fonction** : `AuthController::login()`

    - [ok] [7minute] **Base** : Contrôle du préfixe saisi via la table `Préfixe`. Si valide, recherche dans  `Client`. Si le compte n'existe pas encore, exécution automatique de `ClientModel::insert()` avant d'ouvrir la session utilisateur

    - [ok] [10minute] **Integration** : Interface épurée et centrée (Mobile-first) avec un formulaire Bootstrap contenant un unique champ `<input type="tel">` et message d'erreur en cas de préfixe invalide

- Opérations
    - voir le solde
        - [ok] [10minute] **Page** : `app/Views/client/dashboard.php` (Accueil de l'espace client)
        - **Fonction** : `ClientController::index()` invoquant `OperationModel::calculateSolde($id_client)`
        - [ok] [10minute] **Base** : Table `Operation` (Somme filtrée sur les opérations du client connecté)
        - **Integration** : Bannière ou badge volumineux mis en relief en haut de page affichant le solde disponible formaté en Ariary (ex: `15 000 Ar`)
    - Dépôt 
        - **Page** : `app/Views/client/depot.php` (ou onglet/modal dédié)
        - **Fonction** : `ClientController::storeDepot()`
        - **Base** : Table `Operation` (Insertion d'une ligne avec `id_type_operation` lié au dépôt et `frais_appliqué` = 0)
        - **Integration** : Formulaire à champ unique (Montant) avec alerte Bootstrap de succès dès confirmation (considéré comme automatique)
    - Retrait
        - **Page** : `app/Views/client/retrait.php`
        - **Fonction** : `ClientController::storeRetrait()`
        - **Base** : Tables `Operation` et `bareme_frais` (Vérification et extraction du frais lié à la tranche du montant, et validation stricte de la provision )
        - **Integration** : Formulaire de saisie dynamique avec un script JavaScript (JS) modifiant en temps réel l'affichage des frais et du coût total débité avant soumission
    - Transfert
        - **Page** : `app/Views/client/transfert.php`
        - **Fonction** : `ClientController::storeTransfert()`
        - **Base** : Tables `Operation`, `bareme_frais` et `Préfixe` (Contrôle du préfixe du destinataire, récupération du frais applicable selon le barème de transfert, et validation de la provision suffisante du compte émetteur)
        - **Integration** : Formulaire Bootstrap à double entrée (Numéro de téléphone du destinataire et Montant à envoyer) avec récapitulatif détaillé avant validation
    - Historique des opérations
        - **Page** : `app/Views/client/historique.php` (ou table incluse dans le dashboard)
        - **Fonction** : `ClientController::historique()` exploitant `OperationModel::getHistory($id_client)`
        - **Base** : Tables `Operation` et `Types_Operation` (Sélection triée par ordre chronologique décroissant des transactions où l'utilisateur est l'auteur ou le destinataire)
        - **Integration** : Liste ou tableau Bootstrap avec indicateurs de couleur visuels distinctifs (Vert `text-success` pour les flux entrants comme les dépôts et transferts reçus, Rouge `text-danger` / Sombre pour les flux sortants comme les retraits, transferts émis et frais appliqués)
