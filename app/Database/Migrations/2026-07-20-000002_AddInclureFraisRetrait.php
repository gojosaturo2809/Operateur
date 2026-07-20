<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInclureFraisRetrait extends Migration
{
    public function up()
    {
        if (!in_array('inclure_frais_retrait', $this->db->getFieldNames('operations'), true)) {
            $this->forge->addColumn('operations', [
                'inclure_frais_retrait' => [
                    'type'    => 'INTEGER',
                    'null'    => false,
                    'default' => 0,
                ],
            ]);
        }
    }

    public function down()
    {
        if (in_array('inclure_frais_retrait', $this->db->getFieldNames('operations'), true)) {
            $this->forge->dropColumn('operations', 'inclure_frais_retrait');
        }
    }
}
