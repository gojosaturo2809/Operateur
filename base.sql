-- ============================================================
--  MobiMoney — Schéma SQLite
-- ============================================================

-- 1. Préfixes autorisés par l'opérateur (ex: 033, 037)
CREATE TABLE prefixes (
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE
);

-- 2. Types d'opérations
CREATE TABLE types_operation (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE  -- 'depot', 'retrait', 'transfert'
);

-- 3. Barèmes de frais (modifiable par tranche)
CREATE TABLE bareme_frais (
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min       REAL NOT NULL,
    montant_max       REAL NOT NULL,
    frais             REAL NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES types_operation(id)
);

-- 4. Clients (login automatique via numéro de téléphone)
CREATE TABLE clients (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone TEXT NOT NULL UNIQUE
);

-- 5. Opérations (historique global)
CREATE TABLE operations (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client           INTEGER NOT NULL,
    id_type_operation   INTEGER NOT NULL,
    numero_destinataire TEXT    NULL,     -- Rempli uniquement en cas de transfert
    montant             REAL    NOT NULL,
    frais_applique      REAL    NOT NULL, -- Frais figé au moment de la transaction
    date_operation      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client)         REFERENCES clients(id),
    FOREIGN KEY (id_type_operation) REFERENCES types_operation(id)
);


-- ============================================================
--  VUE : Situation des gains (retraits + transferts uniquement)
-- ============================================================
CREATE VIEW vue_situation_gains AS
SELECT
    t.nom                  AS type_operation,
    COUNT(o.id)            AS volume_transactions,
    SUM(o.montant)         AS volume_financier,
    SUM(o.frais_applique)  AS total_gains
FROM operations o
JOIN types_operation t ON o.id_type_operation = t.id
WHERE t.nom IN ('retrait', 'transfert')
GROUP BY t.nom;

-- ============================================================
--  DONNÉES INITIALES
-- ============================================================


INSERT INTO operateurs (nom_utilisateur, mot_de_passe_hash)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);


CREATE TABLE operateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,               -- Ex: 'MonRéseau', 'Telma', 'Orange', 'Airtel'
    est_principal BOOLEAN DEFAULT FALSE,    -- TRUE pour votre propre service, FALSE pour les autres
    commission_inter_pct DECIMAL(5, 2) DEFAULT 0.00 -- Le % de commission en plus pour les transferts sortants
);

ALTER TABLE prefixes ADD COLUMN id_operateur INT NOT NULL;
ALTER TABLE prefixes ADD CONSTRAINT fk_prefixes_operateurs FOREIGN KEY (id_operateur) REFERENCES operateurs(id);

ALTER TABLE operations 
    -- 1. Permet de savoir vers quel opérateur l'argent est parti (pour la page compensation/clearing)
    ADD COLUMN id_operateur_destination INT DEFAULT NULL, 
    
    -- 2. Flag (0 ou 1) pour savoir si le client a coché "Inclure les frais de retrait"
    ADD COLUMN inclure_frais_retrait TINYINT(1) DEFAULT 0, 
    
    -- 3. Un identifiant unique (UUID ou Timestamp) pour regrouper les transactions issues d'un envoi multiple divisé
    ADD COLUMN batch_envoi_multiple VARCHAR(50) DEFAULT NULL;

ALTER TABLE operations ADD CONSTRAINT fk_operations_operateur_dest FOREIGN KEY (id_operateur_destination) REFERENCES operateurs(id);
