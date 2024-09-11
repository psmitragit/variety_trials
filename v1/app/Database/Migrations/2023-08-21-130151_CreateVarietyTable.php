<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVarietyTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'code' => ['type' => 'varchar', 'constraint' => '50'],
            'brand' => ['type' => 'varchar', 'constraint' => '100', 'null' => true],
            'short_name' => ['type' => 'varchar', 'constraint' => '20', 'null' => true],
            'additional_name' => ['type' => 'varchar', 'constraint' => '20', 'null' => true],
            'herbicide' => ['type' => 'varchar', 'constraint' => '255', 'null' => true],
            'name' => ['type' => 'varchar', 'constraint' => '100', 'null' => true],
            'status' => ['type' => 'tinyint', 'default' => 1, 'comment' => "1: active, 0: Inactive"],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('varieties');
    }

    public function down()
    {
        $this->forge->dropTable('varieties');
    }
}
