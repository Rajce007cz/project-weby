<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TeamsTable extends Migration
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
            'nationality' => ['type' => 'VARCHAR', 'constraint' => 100],
            'points' => ['type' => 'INT', 'constraint' => 11],
            'wins' => ['type' => 'INT', 'constraint' => 11],
            'podiums' => ['type' => 'INT', 'constraint' => 11],
            'wcc' => ['type' => 'INT', 'constraint' => 11],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('teams');
    }

    public function down()
    {
        $this->forge->dropTable('teams');
    }
}
