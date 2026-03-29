<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFechaArranqueAcToViviendas extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('viviendas', [
            'fecha_arranque_ac' => [
                'type'    => 'DATE',
                'null'    => true,
                'after'   => 'fecha_instalacion_ac',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('viviendas', 'fecha_arranque_ac');
    }
}
