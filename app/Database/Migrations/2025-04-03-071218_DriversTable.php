<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DriversTable extends Migration
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
            'first_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'nationality' => ['type' => 'VARCHAR', 'constraint' => 100],
            'dob' => ['type' => 'DATE'],
            'points' => ['type' => 'INT', 'constraint' => 11],
            'win' => ['type' => 'INT', 'constraint' => 11],
            'wdc' => ['type' => 'INT', 'constraint' => 11],
            'image' => ['type' => 'VARCHAR', 'constraint' => 255],
            'deleted_at' => ['type' => 'DATETIME', 'constraint' => 6],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('drivers');
    }

    public function down()
    {
        $this->forge->dropTable('drivers');
    }
}