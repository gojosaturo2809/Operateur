INSERT INTO prefixes (id, prefixe) VALUES 
(1, '033'),
(2, '037');

-- Insertion des types de transactions indispensables
INSERT INTO types_operation (id, nom) VALUES 
(1, 'depot'),
(2, 'retrait'),
(3, 'transfert');

-- Insertion du barème d'exemple complet pour les RETRAITS (id_type_operation = 2)
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais) VALUES
(2, 100, 1000, 50),
(2, 1001, 5000, 50),
(2, 5001, 10000, 100),
(2, 10001, 25000, 200),
(2, 25001, 50000, 400),
(2, 50001, 100000, 800),
(2, 100001, 250000, 1500),
(2, 250001, 500000, 1500),
(2, 500001, 1000000, 2500),
(2, 1000001, 2000000, 3000);

-- Insertion du même barème pour les TRANSFERTS (id_type_operation = 3)
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais) VALUES
(3, 100, 1000, 50),
(3, 1001, 5000, 50),
(3, 5001, 10000, 100),
(3, 10001, 25000, 200),
(3, 25001, 50000, 400),
(3, 50001, 100000, 800),
(3, 100001, 250000, 1500),
(3, 250001, 500000, 1500),
(3, 500001, 1000000, 2500),
(3, 1000001, 2000000, 3000);

-- Pré-enregistrement de quelques clients avec des numéros valides
INSERT INTO clients (id, numero_telephone) VALUES 
(1, '0331234567'),
(2, '0379876543'),
(3, '0331112223');

-- Création d'un historique de transactions fictives pour alimenter les calculs de gains et soldes
INSERT INTO operations (id_client, id_type_operation, numero_destinataire, montant, frais_applique, date_operation) VALUES
-- Client 1 effectue un dépôt initial (Aucun frais)
(1, 1, NULL, 50000.0, 0.0, '2026-07-20 08:00:00'),

-- Client 1 effectue un transfert de 15 000 Ar vers Client 2 (Tranche 10001-25000 -> Frais: 200 Ar)
(1, 3, '0379876543', 15000.0, 200.0, '2026-07-20 08:30:00'),

-- Client 2 effectue un dépôt initial (Aucun frais)
(2, 1, NULL, 10000.0, 0.0, '2026-07-20 09:00:00'),

-- Client 2 effectue un retrait de 5 000 Ar en agence (Tranche 1001-5000 -> Frais: 50 Ar)
(2, 2, NULL, 5000.0, 50.0, '2026-07-20 09:15:00'),

-- Client 3 effectue un dépôt massif (Aucun frais)
(3, 1, NULL, 150000.0, 0.0, '2026-07-20 09:30:00'),

-- Client 3 effectue un retrait de 120 000 Ar (Tranche 100001-250000 -> Frais: 1500 Ar)
(3, 2, NULL, 120000.0, 1500.0, '2026-07-20 10:00:00');