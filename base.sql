-- ============================================================
--  MobiMoney — Schéma SQLite
-- ============================================================

-- 1. Opérateurs télécom (notre réseau + réseaux tiers)
--    est_principal = 1  → notre propre réseau
--    est_principal = 0  → opérateur tiers (inter-réseau)
CREATE TABLE operateurs (
    id                   INTEGER PRIMARY KEY AUTOINCREMENT,
    nom                  TEXT    NOT NULL,
    est_principal        INTEGER NOT NULL DEFAULT 0,
    commission_inter_pct REAL    NOT NULL DEFAULT 0.00
);

-- 2. Préfixes autorisés (liés à leur opérateur)
CREATE TABLE prefixes (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe      TEXT    NOT NULL UNIQUE,
    id_operateur INTEGER NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateurs(id)
);

-- 3. Types d'opérations
CREATE TABLE types_operation (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT    NOT NULL UNIQUE   -- 'depot', 'retrait', 'transfert'
);

-- 4. Barèmes de frais (modifiable par tranche)
CREATE TABLE bareme_frais (
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min       REAL    NOT NULL,
    montant_max       REAL    NOT NULL,
    frais             REAL    NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES types_operation(id)
);

-- 5. Clients (login automatique via numéro de téléphone)
CREATE TABLE clients (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone TEXT    NOT NULL UNIQUE
);

-- 6. Opérations (historique global)
CREATE TABLE operations (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client           INTEGER  NOT NULL,
    id_type_operation   INTEGER  NOT NULL,
    numero_destinataire TEXT     NULL,
    montant             REAL     NOT NULL,
    frais_applique      REAL     NOT NULL DEFAULT 0,
    inclure_frais_retrait INTEGER NOT NULL DEFAULT 0,
    batch_envoi_multiple VARCHAR(50) NULL,
    date_operation      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client)         REFERENCES clients(id),
    FOREIGN KEY (id_type_operation) REFERENCES types_operation(id)
);

-- 7. Administrateurs système (login backoffice)
CREATE TABLE administrateurs (
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_utilisateur   TEXT    NOT NULL UNIQUE,
    mot_de_passe_hash TEXT    NOT NULL,
    date_creation     DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
--  VUES SQL
-- ============================================================

-- Vue globale : gains par type (retrait + transfert)
CREATE VIEW vue_situation_gains AS
SELECT
    t.nom                       AS type_operation,
    COUNT(o.id)                 AS volume_transactions,
    COALESCE(SUM(o.montant), 0) AS volume_financier,
    COALESCE(SUM(o.frais_applique), 0) AS total_gains
FROM operations o
JOIN types_operation t ON o.id_type_operation = t.id
WHERE t.nom IN ('retrait', 'transfert')
GROUP BY t.nom;

-- Vue gains réseau local :
-- Retraits + transferts dont le destinataire est sur notre réseau principal
CREATE VIEW vue_gains_local AS
SELECT
    t.nom AS type_operation,
    COUNT(o.id) AS volume_transactions,
    COALESCE(SUM(o.montant), 0) AS volume_financier,
    COALESCE(SUM(o.frais_applique), 0) AS total_gains
FROM operations o
JOIN types_operation t
    ON o.id_type_operation = t.id
LEFT JOIN prefixes p
    ON p.prefixe = substr(o.numero_destinataire, 1, 3)
LEFT JOIN operateurs op
    ON p.id_operateur = op.id
WHERE
    t.nom = 'retrait'
    OR (
        t.nom = 'transfert'
        AND (
            o.numero_destinataire IS NULL
            OR op.est_principal = 1
        )
    )
GROUP BY t.nom;

-- Vue gains inter-opérateurs :
-- Transferts vers des réseaux tiers enregistrés
CREATE VIEW vue_gains_inter AS
SELECT
    op.id AS operateur_id,
    op.nom AS operateur_tiers,
    op.commission_inter_pct,
    COUNT(o.id) AS volume_transactions,
    COALESCE(SUM(o.montant),0) AS volume_financier,
    COALESCE(SUM(o.frais_applique),0) AS total_gains
FROM operations o
JOIN types_operation t
    ON o.id_type_operation = t.id
JOIN prefixes p
    ON p.prefixe = substr(o.numero_destinataire,1,3)
JOIN operateurs op
    ON p.id_operateur = op.id
WHERE
    t.nom = 'transfert'
    AND op.est_principal = 0
GROUP BY op.id;

CREATE VIEW vue_gains_inter_inconnus AS
SELECT
    COUNT(o.id) AS volume_transactions,
    COALESCE(SUM(o.montant),0) AS volume_financier,
    COALESCE(SUM(o.frais_applique),0) AS total_gains
FROM operations o
JOIN types_operation t
    ON o.id_type_operation = t.id
WHERE
    t.nom = 'transfert'
    AND o.numero_destinataire IS NOT NULL
    AND substr(o.numero_destinataire,1,3) NOT IN
    (
        SELECT prefixe
        FROM prefixes
    );

INSERT INTO administrateurs (nom_utilisateur, mot_de_passe_hash)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);
-- Remplacer par : php -r "echo password_hash('votre_mdp', PASSWORD_BCRYPT);"

CREATE VIEW vue_compensation_operateurs AS
SELECT

op.id AS operateur_id,

op.nom AS operateur_nom,

op.commission_inter_pct,

COUNT(o.id) AS nb_transferts,

SUM(o.montant) AS montant_transfere,

SUM(
o.montant*op.commission_inter_pct/100.0
)

AS commission_a_reverser,

SUM(o.frais_applique)

AS frais_percus,

MIN(o.date_operation)

AS premiere_operation,

MAX(o.date_operation)

AS derniere_operation

FROM operations o

JOIN types_operation t
ON t.id=o.id_type_operation

JOIN prefixes p
ON p.prefixe=substr(o.numero_destinataire,1,3)

JOIN operateurs op
ON op.id=p.id_operateur

WHERE

t.nom='transfert'
AND op.est_principal=0

GROUP BY op.id;

CREATE  VIEW vue_client_solde AS
WITH solde_client AS (
    SELECT
        c.id AS client_id,
        c.numero_telephone,
        COALESCE(SUM(CASE WHEN t.nom = 'depot' THEN o.montant ELSE 0 END), 0) AS total_depots,
        COALESCE(SUM(CASE WHEN t.nom = 'retrait' THEN o.montant+o.frais_applique ELSE 0 END), 0) AS total_retraits,
        COALESCE(SUM(CASE WHEN t.nom = 'transfert' THEN o.montant+o.frais_applique ELSE 0 END), 0) AS total_transferts
    FROM clients c
    LEFT JOIN operations o
        ON c.id = o.id_client
    LEFT JOIN types_operation t
        ON o.id_type_operation = t.id
    GROUP BY c.id, c.numero_telephone
)
SELECT
    client_id,
    numero_telephone,
    total_depots,
    total_retraits,
    total_transferts,
    (total_depots - total_retraits - total_transferts) AS solde
FROM solde_client;


CREATE table pct_epargne(
      id                  INTEGER PRIMARY KEY AUTOINCREMENT,
      id_client           INTEGER  NOT NULL,
      epargne_pct REAL    NOT NULL DEFAULT 0.00 ,
      FOREIGN KEY (id_client)         REFERENCES clients(id)
);
CREATE table epargne(
      id                  INTEGER PRIMARY KEY AUTOINCREMENT,
      id_client           INTEGER  NOT NULL,
      val_epargne REAL    NOT NULL DEFAULT 0.00 ,
       date_epargne      DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (id_client)         REFERENCES clients(id)
);