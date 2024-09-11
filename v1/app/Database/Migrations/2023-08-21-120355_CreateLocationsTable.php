<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'code' => ['type' => 'varchar', 'constraint' => '20'],
            'location' => ['type' => 'varchar', 'constraint' => '100'],
            'farm' => ['type' => 'varchar', 'constraint' => '255'],
            'city_code' => ['type' => 'varchar', 'constraint' => '50'],
            'state_code' => ['type' => 'varchar', 'constraint' => '50'],
            'lat' => ['type' => 'varchar', 'constraint' => '100'],
            'long' => ['type' => 'varchar', 'constraint' => '100'],
            'soil_type' => ['type' => 'varchar', 'constraint' => '100', 'null' => true],
            'irrigation' => ['type' => 'tinyint', 'null' => true, 'comment' => "1: Yes, 0: No"],
            'status' => ['type' => 'tinyint', 'default' => 1, 'comment' => "1: active, 0: Inactive"],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('locations');
    }

    public function down()
    {
        $this->forge->dropTable('locations');
    }
}
