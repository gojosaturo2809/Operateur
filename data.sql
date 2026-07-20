-- ============================================================
-- DONNEES DE TEST MOBIMONEY
-- ============================================================

PRAGMA foreign_keys = ON;


-- ============================================================
-- 1. OPERATEURS
-- ============================================================

INSERT INTO operateurs (nom, est_principal, commission_inter_pct)
VALUES
('MobiMoney', 1, 0.00),
('Telma Money', 0, 2.50),
('Orange Money', 0, 3.00),
('Airtel Money', 0, 2.00);


-- ============================================================
-- 2. PREFIXES
-- ============================================================

-- Réseau principal MobiMoney
INSERT INTO prefixes(prefixe, id_operateur)
VALUES
('033', 1),
('034', 1);

-- Réseaux tiers
INSERT INTO prefixes(prefixe, id_operateur)
VALUES
('038', 2),
('032', 3),
('037', 4);


-- ============================================================
-- 3. TYPES OPERATIONS
-- ============================================================

INSERT INTO types_operation(nom)
VALUES
('depot'),
('retrait'),
('transfert');


-- ============================================================
-- 4. BAREMES FRAIS
-- ============================================================

-- Dépôt
INSERT INTO bareme_frais
(id_type_operation,montant_min,montant_max,frais)
VALUES
(1,0,10000,200),
(1,10001,50000,500),
(1,50001,200000,1000);


-- Retrait
INSERT INTO bareme_frais
(id_type_operation,montant_min,montant_max,frais)
VALUES
(2,0,10000,300),
(2,10001,50000,800),
(2,50001,200000,1500);


-- Transfert
INSERT INTO bareme_frais
(id_type_operation,montant_min,montant_max,frais)
VALUES
(3,0,10000,500),
(3,10001,50000,1000),
(3,50001,200000,2000);



-- ============================================================
-- 5. CLIENTS
-- ============================================================

INSERT INTO clients(numero_telephone)
VALUES
('0331200001'),
('0342200002'),
('0383300003'),
('0324400004'),
('0375500005'),
('0336600006');


-- ============================================================
-- 6. OPERATIONS
-- ============================================================


-- ======================
-- DEPOTS
-- ======================

INSERT INTO operations
(id_client,id_type_operation,numero_destinataire,montant,frais_applique)
VALUES

(1,1,NULL,5000,200),
(2,1,NULL,25000,500),
(3,1,NULL,100000,1000);


-- ======================
-- RETRAITS
-- ======================

INSERT INTO operations
(id_client,id_type_operation,numero_destinataire,montant,frais_applique)
VALUES

(1,2,NULL,10000,300),
(2,2,NULL,40000,800),
(3,2,NULL,80000,1500),
(4,2,NULL,150000,1500);



-- ======================
-- TRANSFERT LOCAL
-- destinataire MobiMoney
-- préfixes 033 / 034
-- ======================

INSERT INTO operations
(id_client,id_type_operation,numero_destinataire,montant,frais_applique)
VALUES

(1,3,'0337700007',5000,500),

(2,3,'0348800008',25000,1000),

(6,3,'0339900009',75000,2000);



-- ======================
-- TRANSFERT INTER OPERATEURS
-- Telma 038
-- ======================

INSERT INTO operations
(id_client,id_type_operation,numero_destinataire,montant,frais_applique)
VALUES

(1,3,'0381100011',10000,500),

(2,3,'0382200022',50000,1000);



-- Orange 032

INSERT INTO operations
(id_client,id_type_operation,numero_destinataire,montant,frais_applique)
VALUES

(3,3,'0323300033',20000,1000),

(4,3,'0324400044',100000,2000);



-- Airtel 037

INSERT INTO operations
(id_client,id_type_operation,numero_destinataire,montant,frais_applique)
VALUES

(5,3,'0375500055',15000,500),

(6,3,'0376600066',80000,2000);



-- ======================
-- TRANSFERT VERS PREFIXE INCONNU
-- ======================

INSERT INTO operations
(id_client,id_type_operation,numero_destinataire,montant,frais_applique)
VALUES

(1,3,'0399900000',30000,1000);



-- ============================================================
-- VERIFICATIONS
-- ============================================================

SELECT * FROM vue_situation_gains;

SELECT * FROM vue_gains_local;

SELECT * FROM vue_gains_inter;

SELECT * FROM vue_gains_inter_inconnus;

SELECT * FROM vue_compensation_operateurs;
