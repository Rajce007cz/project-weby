<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RacesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'country' => ['type' => 'VARCHAR', 'constraint' => 100],
            'date' => ['type' => 'DATE'],
            'season_year' => ['type' => 'YEAR'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('season_year', 'seasons', 'year', 'CASCADE', 'CASCADE');
        $this->forge->createTable('races');
    }

    public function down()
    {
        $this->forge->dropTable('races');
    }
}
