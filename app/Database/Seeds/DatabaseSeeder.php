<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $this->db->table('usuarios')->insert([
            'nombre'     => 'Administrador',
            'apellido'   => 'Sistema',
            'email'      => 'admin@inconel.com',
            'password'   => password_hash('Admin@123', PASSWORD_BCRYPT),
            'rol'        => 'admin',
            'activo'     => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Tecnico user
        $this->db->table('usuarios')->insert([
            'nombre'     => 'Juan',
            'apellido'   => 'González',
            'email'      => 'tecnico@inconel.com',
            'password'   => password_hash('Tecnico@123', PASSWORD_BCRYPT),
            'rol'        => 'tecnico',
            'activo'     => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Sample viviendas
        $tecnicos = $this->db->table('usuarios')->get()->getResultArray();
        $adminId  = $tecnicos[0]['id'];
        $tecId    = $tecnicos[1]['id'];

        $viviendas = [
            ['123 Palm Avenue, Miami, FL 33101', '2023-01-15', 'HDL-2023-001', 'CDS-2023-001', '2023-01-10', $adminId],
            ['456 Ocean Drive, Miami Beach, FL 33139', '2022-06-20', 'HDL-2022-045', 'CDS-2022-045', '2022-06-15', $adminId],
            ['789 Brickell Ave, Miami, FL 33131', '2024-03-05', 'HDL-2024-012', 'CDS-2024-012', '2024-03-01', $tecId],
            ['321 Coral Way, Coral Gables, FL 33134', '2021-11-10', 'HDL-2021-089', 'CDS-2021-089', '2021-11-05', $adminId],
            ['654 SW 8th St, Miami, FL 33130', '2024-08-22', 'HDL-2024-067', 'CDS-2024-067', '2024-08-20', $tecId],
        ];

        foreach ($viviendas as $v) {
            $this->db->table('viviendas')->insert([
                'direccion'            => $v[0],
                'fecha_instalacion_ac' => $v[1],
                'serie_handler'        => $v[2],
                'serie_condenser'      => $v[3],
                'fecha_venta'          => $v[4],
                'tecnico_id'           => $v[5],
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
