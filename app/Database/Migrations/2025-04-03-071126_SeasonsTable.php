<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeasonsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'year' => [
                'type' => 'YEAR',
                'unique' => true,
            ],
            'description' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('year');
        $this->forge->createTable('seasons');
    }

    public function down()
    {
        $this->forge->dropTable('seasons');
    }
}