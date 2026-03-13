<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateViviendasTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => 300,
                'null'       => false,
            ],
            'fecha_instalacion_ac' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'serie_handler' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'serie_condenser' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'fecha_venta' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'tecnico_id' => [
                'type'     => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null'     => false,
            ],
            'notas' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tecnico_id');
        $this->forge->addKey('fecha_venta');
        $this->forge->addForeignKey('tecnico_id', 'usuarios', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('viviendas');
    }

    public function down(): void
    {
        $this->forge->dropTable('viviendas');
    }
}
