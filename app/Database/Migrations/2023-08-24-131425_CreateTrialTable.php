<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrialTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'int', 'auto_increment' => true],
            'crop_id'           => ['type' => 'int'],
            'user_id'           => ['type' => 'int'],
            'name'              => ['type' => 'varchar', 'constraint' => 255],
            'trial_type_id'     => ['type' => 'int'],
            'year'              => ['type' => 'int', 'constraint' => 4],
            'locations'         => ['type' => 'json'],
            'planting_date'     => ['type' => 'date', 'null' => true],
            'harvest_date'      => ['type' => 'date', 'null' => true],
            'status'            => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1, 'comment' => '1: Active, 0: Not Active'],
            'created_at'        => ['type' => 'timestamp', 'null' => true],
            'updated_at'        => ['type' => 'timestamp', 'null' => true],
            'deleted_at'        => ['type' => 'timestamp', 'null' => true]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('trials');
    }

    public function down()
    {
        $this->forge->dropTable('trials');
    }
}
