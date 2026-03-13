<?php

namespace App\Models;

use CodeIgniter\Model;

class ViviendaModel extends Model
{
    protected $table            = 'viviendas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'direccion',
        'fecha_instalacion_ac',
        'serie_handler',
        'serie_condenser',
        'fecha_venta',
        'tecnico_id',
        'notas',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'direccion'            => 'required|min_length[5]|max_length[300]',
        'fecha_instalacion_ac' => 'required|valid_date',
        'serie_handler'        => 'required|max_length[100]',
        'serie_condenser'      => 'required|max_length[100]',
        'fecha_venta'          => 'required|valid_date',
        'tecnico_id'           => 'required|integer',
    ];

    protected $validationMessages = [
        'direccion'            => ['required' => 'La dirección es requerida.'],
        'fecha_instalacion_ac' => ['required' => 'La fecha de instalación es requerida.'],
        'serie_handler'        => ['required' => 'El número de serie del handler es requerido.'],
        'serie_condenser'      => ['required' => 'El número de serie del condenser es requerido.'],
        'fecha_venta'          => ['required' => 'La fecha de venta es requerida.'],
        'tecnico_id'           => ['required' => 'El técnico es requerido.'],
    ];

    /**
     * Get viviendas with technician name (JOIN)
     */
    public function getWithTecnico(?int $id = null): array
    {
        $builder = $this->db->table('viviendas v')
            ->select('v.*, u.nombre AS tecnico_nombre, u.apellido AS tecnico_apellido,
                      CONCAT(u.nombre, " ", u.apellido) AS tecnico_nombre_completo')
            ->join('usuarios u', 'u.id = v.tecnico_id')
            ->where('v.deleted_at IS NULL');

        if ($id !== null) {
            return $builder->where('v.id', $id)->get()->getRowArray() ?? [];
        }

        return $builder->orderBy('v.created_at', 'DESC')->get()->getResultArray();
    }

    /**
     * Get DataTable data with warranty status
     */
    public function getDatatableData(array $params): array
    {
        $builder = $this->db->table('viviendas v')
            ->select("v.id, v.direccion, v.fecha_instalacion_ac, v.serie_handler,
                      v.serie_condenser, v.fecha_venta,
                      CONCAT(u.nombre, ' ', u.apellido) AS tecnico,
                      v.created_at,
                      CASE
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) < CURDATE() THEN 'vencida'
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'por_vencer'
                        ELSE 'activa'
                      END AS garantia_mano_obra,
                      CASE
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) < CURDATE() THEN 'vencida'
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY) THEN 'por_vencer'
                        ELSE 'activa'
                      END AS garantia_equipamiento")
            ->join('usuarios u', 'u.id = v.tecnico_id')
            ->where('v.deleted_at IS NULL');

        // Global search
        if (! empty($params['search']['value'])) {
            $search = $params['search']['value'];
            $builder->groupStart()
                    ->like('v.direccion', $search)
                    ->orLike('v.serie_handler', $search)
                    ->orLike('v.serie_condenser', $search)
                    ->orLike('u.nombre', $search)
                    ->orLike('u.apellido', $search)
                    ->groupEnd();
        }

        $total    = $this->db->table('viviendas')->where('deleted_at IS NULL')->countAllResults();
        $filtered = $builder->countAllResults(false);

        // Order
        $columns = ['v.id', 'v.direccion', 'v.fecha_instalacion_ac', 'v.serie_handler',
                    'v.serie_condenser', 'v.fecha_venta', 'tecnico', 'v.created_at'];
        if (isset($params['order'][0])) {
            $col = $columns[$params['order'][0]['column']] ?? 'v.id';
            $dir = $params['order'][0]['dir'] === 'asc' ? 'ASC' : 'DESC';
            $builder->orderBy($col, $dir);
        }

        $start  = (int) ($params['start'] ?? 0);
        $length = (int) ($params['length'] ?? 10);
        $data   = $builder->limit($length, $start)->get()->getResultArray();

        return [
            'draw'            => (int) ($params['draw'] ?? 1),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ];
    }

    /**
     * Get warranty report data
     */
    public function getWarrantyReport(array $filters = []): array
    {
        $builder = $this->db->table('viviendas v')
            ->select("v.id, v.direccion, v.fecha_instalacion_ac, v.serie_handler,
                      v.serie_condenser, v.fecha_venta,
                      CONCAT(u.nombre, ' ', u.apellido) AS tecnico,
                      DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) AS vencimiento_mano_obra,
                      DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) AS vencimiento_equipamiento,
                      CASE
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) < CURDATE() THEN 'vencida'
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'por_vencer'
                        ELSE 'activa'
                      END AS garantia_mano_obra,
                      CASE
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) < CURDATE() THEN 'vencida'
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY) THEN 'por_vencer'
                        ELSE 'activa'
                      END AS garantia_equipamiento,
                      DATEDIFF(DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR), CURDATE()) AS dias_garantia_labor,
                      DATEDIFF(DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR), CURDATE()) AS dias_garantia_equipamiento")
            ->join('usuarios u', 'u.id = v.tecnico_id')
            ->where('v.deleted_at IS NULL');

        if (! empty($filters['tecnico_id'])) {
            $builder->where('v.tecnico_id', $filters['tecnico_id']);
        }

        if (! empty($filters['garantia_mano_obra'])) {
            switch ($filters['garantia_mano_obra']) {
                case 'activa':
                    $builder->where('DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) >= CURDATE()');
                    break;
                case 'vencida':
                    $builder->where('DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) < CURDATE()');
                    break;
                case 'por_vencer':
                    $builder->where('DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)');
                    break;
            }
        }

        if (! empty($filters['garantia_equipamiento'])) {
            switch ($filters['garantia_equipamiento']) {
                case 'activa':
                    $builder->where('DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) >= CURDATE()');
                    break;
                case 'vencida':
                    $builder->where('DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) < CURDATE()');
                    break;
                case 'por_vencer':
                    $builder->where('DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY)');
                    break;
            }
        }

        return $builder->orderBy('v.fecha_venta', 'ASC')->get()->getResultArray();
    }

    /**
     * Get warranty DataTable data
     */
    public function getWarrantyDatatableData(array $params): array
    {
        $builder = $this->db->table('viviendas v')
            ->select("v.id, v.direccion, v.fecha_venta,
                      CONCAT(u.nombre, ' ', u.apellido) AS tecnico,
                      DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) AS vencimiento_mano_obra,
                      DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) AS vencimiento_equipamiento,
                      CASE
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) < CURDATE() THEN 'vencida'
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'por_vencer'
                        ELSE 'activa'
                      END AS garantia_mano_obra,
                      CASE
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) < CURDATE() THEN 'vencida'
                        WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY) THEN 'por_vencer'
                        ELSE 'activa'
                      END AS garantia_equipamiento")
            ->join('usuarios u', 'u.id = v.tecnico_id')
            ->where('v.deleted_at IS NULL');

        if (! empty($params['search']['value'])) {
            $search = $params['search']['value'];
            $builder->groupStart()
                    ->like('v.direccion', $search)
                    ->orLike('u.nombre', $search)
                    ->orLike('u.apellido', $search)
                    ->groupEnd();
        }

        $total    = $this->db->table('viviendas')->where('deleted_at IS NULL')->countAllResults();
        $filtered = $builder->countAllResults(false);

        $start  = (int) ($params['start'] ?? 0);
        $length = (int) ($params['length'] ?? 10);
        $data   = $builder->orderBy('v.fecha_venta', 'ASC')->limit($length, $start)->get()->getResultArray();

        return [
            'draw'            => (int) ($params['draw'] ?? 1),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ];
    }

    /**
     * Get summary statistics
     */
    public function getSummaryStats(): array
    {
        $result = $this->db->query("
            SELECT
                COUNT(*) AS total_viviendas,
                SUM(CASE WHEN DATE_ADD(fecha_venta, INTERVAL 1 YEAR) >= CURDATE() THEN 1 ELSE 0 END) AS garantia_labor_activa,
                SUM(CASE WHEN DATE_ADD(fecha_venta, INTERVAL 1 YEAR) < CURDATE() THEN 1 ELSE 0 END) AS garantia_labor_vencida,
                SUM(CASE WHEN DATE_ADD(fecha_venta, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) AS garantia_labor_por_vencer,
                SUM(CASE WHEN DATE_ADD(fecha_venta, INTERVAL 10 YEAR) >= CURDATE() THEN 1 ELSE 0 END) AS garantia_equip_activa,
                SUM(CASE WHEN DATE_ADD(fecha_venta, INTERVAL 10 YEAR) < CURDATE() THEN 1 ELSE 0 END) AS garantia_equip_vencida,
                SUM(CASE WHEN DATE_ADD(fecha_venta, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY) THEN 1 ELSE 0 END) AS garantia_equip_por_vencer
            FROM viviendas
            WHERE deleted_at IS NULL
        ")->getRowArray();

        return $result ?? [];
    }

    /**
     * Get report by technician
     */
    public function getByTecnicoDatatable(array $params): array
    {
        $builder = $this->db->table('viviendas v')
            ->select("CONCAT(u.nombre, ' ', u.apellido) AS tecnico, u.id AS tecnico_id,
                      COUNT(v.id) AS total,
                      SUM(CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) >= CURDATE() THEN 1 ELSE 0 END) AS labor_activa,
                      SUM(CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) < CURDATE() THEN 1 ELSE 0 END) AS labor_vencida,
                      SUM(CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) >= CURDATE() THEN 1 ELSE 0 END) AS equip_activa,
                      SUM(CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) < CURDATE() THEN 1 ELSE 0 END) AS equip_vencida")
            ->join('usuarios u', 'u.id = v.tecnico_id')
            ->where('v.deleted_at IS NULL')
            ->groupBy('u.id, u.nombre, u.apellido');

        $total    = count($builder->get()->getResultArray());
        $filtered = $total;

        $data = $this->db->table('viviendas v')
            ->select("CONCAT(u.nombre, ' ', u.apellido) AS tecnico, u.id AS tecnico_id,
                      COUNT(v.id) AS total,
                      SUM(CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) >= CURDATE() THEN 1 ELSE 0 END) AS labor_activa,
                      SUM(CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 1 YEAR) < CURDATE() THEN 1 ELSE 0 END) AS labor_vencida,
                      SUM(CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) >= CURDATE() THEN 1 ELSE 0 END) AS equip_activa,
                      SUM(CASE WHEN DATE_ADD(v.fecha_venta, INTERVAL 10 YEAR) < CURDATE() THEN 1 ELSE 0 END) AS equip_vencida")
            ->join('usuarios u', 'u.id = v.tecnico_id')
            ->where('v.deleted_at IS NULL')
            ->groupBy('u.id, u.nombre, u.apellido')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        return [
            'draw'            => (int) ($params['draw'] ?? 1),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ];
    }
}
