-- 1. S'assurer que les types d'opérations de base existent
INSERT OR IGNORE INTO types_operation (nom) VALUES 
('depot'), 
('retrait'), 
('transfert');

-- 2. Insertion des barèmes par tranche de montant
-- Remplacez 'retrait' par 'transfert' si ce barème s'applique aux transferts
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais) VALUES
((SELECT id FROM types_operation WHERE nom = 'retrait'), 100, 1000, 50),
((SELECT id FROM types_operation WHERE nom = 'retrait'), 1001, 5000, 50),
((SELECT id FROM types_operation WHERE nom = 'retrait'), 5001, 10000, 100),
((SELECT id FROM types_operation WHERE nom = 'retrait'), 10001, 25000, 200),
((SELECT id FROM types_operation WHERE nom = 'retrait'), 25001, 50000, 400),
((SELECT id FROM types_operation WHERE nom = 'retrait'), 50001, 100000, 800),
((SELECT id FROM types_operation WHERE nom = 'retrait'), 100001, 250000, 1500),
((SELECT id FROM types_operation WHERE nom = 'retrait'), 250001, 500000, 1500),
((SELECT id FROM types_operation WHERE nom = 'retrait'), 500001, 1000000, 2500),
((SELECT id FROM types_operation WHERE nom = 'retrait'), 1000001, 2000000, 3000);