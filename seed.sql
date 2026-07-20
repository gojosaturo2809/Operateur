-- =========================================
-- 1. Préfixes autorisés
-- =========================================
INSERT INTO prefixes (prefixe) VALUES
('033'),
('034'),
('032'),
('037');


-- =========================================
-- 2. Types d'opérations
-- =========================================
INSERT INTO types_operation (nom) VALUES
('depot'),
('retrait'),
('transfert');


-- =========================================
-- 3. Barème des frais
-- =========================================
-- Dépôt
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais)
VALUES
(1, 0, 10000, 200),
(1, 10001, 50000, 500),
(1, 50001, 1000000, 1000);

-- Retrait
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais)
VALUES
(2, 0, 10000, 500),
(2, 10001, 50000, 1000),
(2, 50001, 1000000, 2000);

-- Transfert
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais)
VALUES
(3, 0, 10000, 300),
(3, 10001, 50000, 700),
(3, 50001, 1000000, 1500);


-- =========================================
-- 4. Clients
-- =========================================
INSERT INTO clients (numero_telephone) VALUES
('0331234567'),
('0349876543'),
('0325557788'),
('0371122334'),
('0339988776');


-- =========================================
-- 5. Opérations
-- =========================================

-- DEPOTS (ne comptent pas dans les gains)
INSERT INTO operations
(id_client, id_type_operation, numero_destinataire, montant, frais_applique)
VALUES
(1, 1, NULL, 5000, 200),
(2, 1, NULL, 30000, 500),
(3, 1, NULL, 150000, 1000);


-- RETRAITS
INSERT INTO operations
(id_client, id_type_operation, numero_destinataire, montant, frais_applique)
VALUES
(1, 2, NULL, 10000, 500),
(2, 2, NULL, 25000, 1000),
(3, 2, NULL, 80000, 2000),
(4, 2, NULL, 5000, 500);


-- TRANSFERTS
INSERT INTO operations
(id_client, id_type_operation, numero_destinataire, montant, frais_applique)
VALUES
(1, 3, '0349876543', 7000, 300),
(2, 3, '0331234567', 20000, 700),
(3, 3, '0371122334', 60000, 1500),
(5, 3, '0325557788', 120000, 1500);