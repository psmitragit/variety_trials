<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCropTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'name' => ['type' => 'varchar', 'constraint' => '100'],
            'slug' => ['type' => 'varchar', 'constraint' => '100'],
            'status' => ['type' => 'tinyint', 'default' => 1, 'comment' => "1: active, 0: Inactive"],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('crops');
    }

    public function down()
    {
        $this->forge->dropTable('crops');
    }
}
