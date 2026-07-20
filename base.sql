-- 1. Table des préfixes autorisés par l'opérateur (ex: 033, 037)
CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE
);

-- 2. Table des types d'opérations
CREATE TABLE types_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE -- 'depot', 'retrait', 'transfert'
);

-- 3. Table des barèmes de frais (Modifiable par tranche)
CREATE TABLE bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES types_operation(id)
);

-- 4. Table des Clients (Login automatique via numéro de téléphone)
CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone TEXT NOT NULL UNIQUE
);

-- 5. Table des Opérations (Historique global)
CREATE TABLE operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client INTEGER NOT NULL,
    id_type_operation INTEGER NOT NULL,
    numero_destinataire TEXT NULL, -- Rempli uniquement en cas de transfert
    montant REAL NOT NULL,
    frais_applique REAL NOT NULL,  -- Le frais figé au moment de la transaction
    date_operation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id),
    FOREIGN KEY (id_type_operation) REFERENCES types_operation(id)
);

-- 6. VUE SQL : Situation des gains via les frais (Retrait et Transfert uniquement)
CREATE VIEW vue_situation_gains AS
SELECT 
    t.nom AS type_operation,
    COUNT(o.id) AS volume_transactions,
    SUM(o.montant) AS volume_financier,
    SUM(o.frais_applique) AS total_gains
FROM operations o
JOIN types_operation t ON o.id_type_operation = t.id
WHERE t.nom IN ('retrait', 'transfert')
GROUP BY t.nom;-- 1. Table des préfixes autorisés par l'opérateur (ex: 033, 037)
CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE
);

-- 2. Table des types d'opérations
CREATE TABLE types_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE -- 'depot', 'retrait', 'transfert'
);

-- 3. Table des barèmes de frais (Modifiable par tranche)
CREATE TABLE bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES types_operation(id)
);

-- 4. Table des Clients (Login automatique via numéro de téléphone)
CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone TEXT NOT NULL UNIQUE
);

-- 5. Table des Opérations (Historique global)
CREATE TABLE operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client INTEGER NOT NULL,
    id_type_operation INTEGER NOT NULL,
    numero_destinataire TEXT NULL, -- Rempli uniquement en cas de transfert
    montant REAL NOT NULL,
    frais_applique REAL NOT NULL,  -- Le frais figé au moment de la transaction
    date_operation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id),
    FOREIGN KEY (id_type_operation) REFERENCES types_operation(id)
);

-- 6. VUE SQL : Situation des gains via les frais (Retrait et Transfert uniquement)
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