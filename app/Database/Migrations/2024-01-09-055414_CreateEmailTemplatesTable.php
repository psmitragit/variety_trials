<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailTemplatesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'name' => ['type' => 'varchar', 'constraint' => 255],
            'code' => ['type' => 'varchar', 'constraint' => 255],
            'subject' => ['type' => 'varchar', 'constraint' => 255],
            'placeholder' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'content' => ['type' => 'text', 'null' => true],
            'status' => ['type' => 'tinyint', 'constraint' => '1', 'default' => 1, 'comment' => '1: Active, 0: Inactive'],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('email_templates');
    }

    public function down()
    {
        $this->forge->dropTable('email_templates');
    }
}
