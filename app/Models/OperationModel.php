<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operations';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_client', 'id_type_operation', 'numero_destinataire', 'montant', 'frais_applique', 'inclure_frais_retrait', 'batch_envoi_multiple', 'date_operation'];

    /**
     * Calcule le solde exact du client (Dépôts + Transferts reçus) - (Retraits + Transferts émis + Frais émis)
     */
    public function calculateSolde(int $id_client, string $telephone): float
    {
        $db = db_connect();

        // 1. Somme des flux initiés par l'utilisateur (Débits de retraits/transferts et Crédits de dépôts)
        $fluxAuteur = $db->query("
            SELECT SUM(CASE 
                WHEN id_type_operation = 1 THEN montant -- Dépôt (+)
                WHEN id_type_operation = 2 THEN -(montant + frais_applique) -- Retrait (-)
                WHEN id_type_operation = 3 THEN -(montant + frais_applique) -- Transfert Émis (-)
                ELSE 0 END) as total 
            FROM operations WHERE id_client = ?
        ", [$id_client])->getRow();

        // 2. Somme des transferts reçus depuis un autre utilisateur (Crédits (+))
        $fluxRecu = $db->query("
            SELECT SUM(montant) as total 
            FROM operations 
            WHERE id_type_operation = 3 AND numero_destinataire = ?
        ", [$telephone])->getRow();

        $totalAuteur = (float)($fluxAuteur->total ?? 0);
        $totalRecu   = (float)($fluxRecu->total ?? 0);
        $totalEpargne = (float) (($db->query(
            "SELECT COALESCE(SUM(val_epargne), 0) AS total FROM epargne WHERE id_client = ?",
            [$id_client]
        )->getRow()->total ?? 0));

        return $totalAuteur + $totalRecu - $totalEpargne;
        
    }
        public function findEpargne(int $id_client, string $telephone): float
    {
        $db = db_connect();
        $result = $db->query(
            "SELECT COALESCE(SUM(val_epargne), 0) AS total FROM epargne WHERE id_client = ?",
            [$id_client]
        )->getRow();

        return (float) ($result->total ?? 0);
    }
  
    public function getFraisApplicable(int $id_type_operation, float $montant): ?float
    {
        $db = db_connect();
        $result = $db->query("
            SELECT frais FROM bareme_frais 
            WHERE id_type_operation = ? AND ? BETWEEN montant_min AND montant_max
            LIMIT 1
        ", [$id_type_operation, $montant])->getRow();

        return $result ? (float)$result->frais : null;
    }

    /**
     * Récupère l'historique complet trié par ordre chronologique décroissant (Auteur ou Destinataire)
     */
    public function getHistory(int $id_client, string $telephone): array
    {
        $db = db_connect();
        return $db->query("
            SELECT o.*, t.nom as type_nom, c.numero_telephone as auteur_telephone
            FROM operations o
            JOIN types_operation t ON o.id_type_operation = t.id
            JOIN clients c ON o.id_client = c.id
            WHERE o.id_client = ? OR (o.id_type_operation = 3 AND o.numero_destinataire = ?)
            ORDER BY o.date_operation DESC
        ", [$id_client, $telephone])->getResultArray();
    }
}
