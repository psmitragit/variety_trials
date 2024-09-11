<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrialTypeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'crop_id' => ['type' => 'int'],
            'name' => ['type' => 'varchar', 'constraint' => '100'],
            'status' => ['type' => 'tinyint', 'constraint' => '1', 'default' => 1, 'comment' => '1: Active, 0: Inactive'],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('trial_types');
    }

    public function down()
    {
        $this->forge->dropTable('trial_types');
    }
}
