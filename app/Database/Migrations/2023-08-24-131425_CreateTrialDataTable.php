<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrialTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'crop_id' => ['type' => 'int'],
            'user_id' => ['type' => 'int'],
            'year' => ['type' => 'int', 'constraint' => '4', 'null' => true],
            'state_code' => ['type' => 'varchar', 'constraint' => '100', 'null' => true],
            'program' => ['type' => 'int'],
            'trial' => ['type' => 'varchar', 'constraint' => '100', 'null' => true],
            'location_code' => ['type' => 'varchar', 'constraint' => '100', 'null' => true],
            'location ' => ['type' => 'varchar', 'constraint' => '100', 'null' => true],
            'variety_code' => ['type' => 'varchar', 'constraint' => '100', 'null' => true],
            'entry' => ['type' => 'varchar', 'constraint' => '20', 'null' => true],
            'variable' => ['type' => 'json', 'null' => true],
            'is_approved' => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0, 'comment' => '1: Approved, 0: Unapproved'],
            'status' => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1, 'comment' => '1: Active, 0: Not Active'],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('trial_data');
    }

    public function down()
    {
        $this->forge->dropTable('trial_data');
    }
}
