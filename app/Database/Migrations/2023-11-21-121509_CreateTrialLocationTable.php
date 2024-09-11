<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrialLocationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'trial_id'      => ['type' => 'int'],
            'location_id'   => ['type' => 'int'],
            'harvest_date'  => ['type' => 'date', 'null' => true]
        ]);
        $this->forge->createTable('trial_location');
    }

    public function down()
    {
        $this->forge->dropTable('trial_location');
    }
}
