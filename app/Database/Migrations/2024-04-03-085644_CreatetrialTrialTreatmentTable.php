<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatetrialTrialTreatmentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'trial_id' => ['type' => 'int'],
            'treatment_id' => ['type' => 'int'],
        ]);
        $this->forge->createTable('trial_treatment');
    }

    public function down()
    {
        $this->forge->dropTable('trial_treatment');
    }
}
