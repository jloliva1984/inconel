<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ViviendaModel;

class Dashboard extends BaseController
{
    protected UsuarioModel $usuarioModel;
    protected ViviendaModel $viviendaModel;
    protected \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db            = \Config\Database::connect();
        $this->usuarioModel  = new UsuarioModel();
        $this->viviendaModel = new ViviendaModel();
    }

    /**
     * Dashboard main page
     */
    public function index(): string
    {
        $stats = $this->viviendaModel->getSummaryStats();

        $totalUsuarios = $this->usuarioModel
            ->where('deleted_at IS NULL')
            ->where('activo', 1)
            ->countAllResults();

        // Recent viviendas
        $recientes = $this->db->table('viviendas v')
            ->select("v.id, v.direccion, v.fecha_venta,
                      CONCAT(u.nombre, ' ', u.apellido) AS tecnico,
                      CASE
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) < CURDATE() THEN 'vencida'
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'por_vencer'
                        ELSE 'activa'
                      END AS garantia_mano_obra")
            ->join('usuarios u', 'u.id = v.tecnico_id')
            ->where('v.deleted_at IS NULL')
            ->orderBy('v.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Viviendas próximas a vencer garantía laboral (30 días)
        $porVencer = $this->db->query("
            SELECT v.id, v.direccion, v.fecha_venta,
                   DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) AS vencimiento_labor,
                   DATEDIFF(DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR), CURDATE()) AS dias_restantes,
                   CONCAT(u.nombre, ' ', u.apellido) AS tecnico
            FROM viviendas v
            JOIN usuarios u ON u.id = v.tecnico_id
            WHERE v.deleted_at IS NULL
              AND DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY vencimiento_labor ASC
            LIMIT 5
        ")->getResultArray();

        return view('dashboard/index', [
            'title'         => 'Dashboard - Inconel Building',
            'stats'         => $stats,
            'totalUsuarios' => $totalUsuarios,
            'recientes'     => $recientes,
            'porVencer'     => $porVencer,
        ]);
    }
}
