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
    t.nom                       AS type_operation,
    COUNT(o.id)                 AS volume_transactions,
    COALESCE(SUM(o.montant), 0) AS volume_financier,
    COALESCE(SUM(o.frais_applique), 0) AS total_gains
FROM operations o
JOIN types_operation t ON o.id_type_operation = t.id
WHERE
    t.nom = 'retrait'
    OR (
        t.nom = 'transfert'
        AND (
            o.numero_destinataire IS NULL
            OR substr(o.numero_destinataire, 1, 3) IN (
                SELECT p.prefixe FROM prefixes p
                JOIN operateurs op ON p.id_operateur = op.id
                WHERE op.est_principal = 1
            )
        )
    )
GROUP BY t.nom;

-- Vue gains inter-opérateurs :
-- Transferts vers des réseaux tiers enregistrés
CREATE VIEW vue_gains_inter AS
SELECT
    op.nom                            AS operateur_tiers,
    op.commission_inter_pct,
    COUNT(o.id)                       AS volume_transactions,
    COALESCE(SUM(o.montant), 0)       AS volume_financier,
    COALESCE(SUM(o.frais_applique), 0) AS total_gains
FROM operations o
JOIN types_operation t   ON o.id_type_operation = t.id
JOIN prefixes p          ON p.prefixe = substr(o.numero_destinataire, 1, 3)
JOIN operateurs op       ON p.id_operateur = op.id
WHERE
    t.nom = 'transfert'
    AND o.numero_destinataire IS NOT NULL
    AND op.est_principal = 0
GROUP BY op.id;

-- ============================================================
--  DONNÉES INITIALES
-- ============================================================

-- Types d'opérations de base
INSERT INTO types_operation (nom) VALUES ('depot'), ('retrait'), ('transfert');

-- Opérateur principal (notre réseau)
INSERT INTO operateurs (nom, est_principal, commission_inter_pct)
VALUES ('MonRéseau', 1, 0.00);

-- Exemple d'opérateurs tiers
-- INSERT INTO operateurs (nom, est_principal, commission_inter_pct)
-- VALUES ('Orange', 0, 1.50), ('Airtel', 0, 2.00), ('Telma', 0, 1.75);

-- Préfixes de notre réseau (id_operateur = 1)
-- INSERT INTO prefixes (prefixe, id_operateur) VALUES ('033', 1), ('037', 1);

-- Administrateur par défaut : admin / password
-- Hash : password_hash('password', PASSWORD_BCRYPT)
INSERT INTO administrateurs (nom_utilisateur, mot_de_passe_hash)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);
-- Remplacer par : php -r "echo password_hash('votre_mdp', PASSWORD_BCRYPT);"
