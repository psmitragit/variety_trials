<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUploadTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'crop_id' => ['type' => 'int'],
            'state_code' => ['type' => 'varchar', 'constraint' => '20', 'null' => true],
            'year' => ['type' => 'int', 'constraint' => '4'],
            'title' => ['type' => 'varchar', 'constraint' => '255'],
            'url' => ['type' => 'varchar', 'constraint' => '255'],
            'status' => ['type' => 'tinyint', 'constraint' => '1', 'default' => 1, 'comment' => '1: Active, 0: Inactive'],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('uploads');
    }

    public function down()
    {
        $this->forge->dropTable('uploads');
    }
}
