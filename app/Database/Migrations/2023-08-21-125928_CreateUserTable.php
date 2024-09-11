<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'username' => ['type' => 'varchar', 'constraint' => '50'],
            'name' => ['type' => 'varchar', 'constraint' => '100'],
            'email' => ['type' => 'varchar', 'constraint' => '100'],
            'phone' => ['type' => 'varchar', 'constraint' => '20', 'null' => true],
            'university' => ['type' => 'varchar', 'constraint' => '255', 'null' => true],
            'state' => ['type' => 'smallint', 'null' => true],
            'crop' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'password' => ['type' => 'varchar', 'constraint' => '255'],
            'type' => ['type' => 'tinyint', 'default' => 2, 'comment' => "2: user,1: Staff, 0: Super Admin"],
            'status' => ['type' => 'tinyint', 'default' => 1, 'comment' => "1: active, 0: Inactive"],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
