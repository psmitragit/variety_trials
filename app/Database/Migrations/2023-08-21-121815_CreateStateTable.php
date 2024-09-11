<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStateTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'code' => ['type' => 'varchar', 'constraint' => '20'],
            'name' => ['type' => 'varchar', 'constraint' => 150, 'null' => true],
            'country_id' => ['type' => 'int', 'default' => 225],
            'status' => ['type' => 'tinyint', 'default' => 1, 'comment' => "1: active, 0: Inactive"],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('states');
    }

    public function down()
    {
        $this->forge->dropTable('states');
    }
}
