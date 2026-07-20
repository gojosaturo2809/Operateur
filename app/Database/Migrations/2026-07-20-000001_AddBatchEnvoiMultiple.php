<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBatchEnvoiMultiple extends Migration
{
    public function up()
    {
        if (!in_array('batch_envoi_multiple', $this->db->getFieldNames('operations'), true)) {
            $this->forge->addColumn('operations', [
                'batch_envoi_multiple' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if (in_array('batch_envoi_multiple', $this->db->getFieldNames('operations'), true)) {
            $this->forge->dropColumn('operations', 'batch_envoi_multiple');
        }
    }
}
