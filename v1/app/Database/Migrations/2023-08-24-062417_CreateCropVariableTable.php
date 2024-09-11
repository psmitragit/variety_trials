<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCropVariableTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'crop_id' => ['type' => 'int'],
            'name' => ['type' => 'varchar', 'constraint' => '100', 'null' => true],
            'status' => ['type' => 'tinyint', 'default' => 1, 'comment' => "1: active, 0: Inactive"],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('crop_variables');
    }

    public function down()
    {
        $this->forge->dropTable('crop_variables');
    }
}
