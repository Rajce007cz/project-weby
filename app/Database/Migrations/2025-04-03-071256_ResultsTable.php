<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResultsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'race_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'driver_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'team_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'position' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'points' => [
                'type'       => 'FLOAT',
                'constraint' => '5,2',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('race_id', 'races', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('driver_id', 'drivers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('team_id', 'teams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('results');
    }

    public function down()
    {
        $this->forge->dropTable('results');
    }
}