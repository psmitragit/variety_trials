<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTreatmentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'crop_id' => ['type' => 'int'],
            'variety_id' => ['type' => 'int'],
            'name' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_id' => ['type' => 'int'],
            'mat' => ['type' => 'varchar', 'constraint' => '255', 'null' => true],
            'herbicide' => ['type' => 'varchar', 'constraint' => '255', 'null' => true],
            'insecticide' => ['type' => 'varchar', 'constraint' => '255', 'null' => true],
            'refuge' => ['type' => 'enum("Y","N")', 'default' => null, 'null' => true],
            'seed_treatment' => ['type' => 'varchar', 'constraint' => '255', 'null' => true],
            'is_approved' => ['type' => 'tinyint', 'constraint' => '1', 'default' => 0, 'comment' => '1: Approved, 0: Un Approved'],
            'approved_by' => ['type' => 'int', 'null' => true],
            'status' => ['type' => 'tinyint', 'constraint' => '1', 'default' => 1, 'comment' => '1: Active, 0: Inactive'],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
            'deleted_at' => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('treatments');
    }

    public function down()
    {
        $this->forge->dropTable('treatments');
    }
}
