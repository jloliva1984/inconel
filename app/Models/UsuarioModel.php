<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'nombre',
        'apellido',
        'email',
        'password',
        'rol',
        'activo',
        'ultimo_login',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'nombre'   => 'required|min_length[2]|max_length[100]',
        'apellido' => 'required|min_length[2]|max_length[100]',
        'email'    => 'required|valid_email|max_length[150]|is_unique[usuarios.email,id,{id}]',
        'rol'      => 'required|in_list[admin,tecnico]',
    ];

    protected $validationMessages = [
        'nombre'   => ['required' => 'El nombre es requerido.'],
        'apellido' => ['required' => 'El apellido es requerido.'],
        'email'    => [
            'required'    => 'El correo electrónico es requerido.',
            'valid_email' => 'El correo electrónico no es válido.',
            'is_unique'   => 'Este correo ya está registrado.',
        ],
        'rol'      => ['required' => 'El rol es requerido.'],
    ];

    protected $skipValidation = false;

    /**
     * Find user by email for login
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)
                    ->where('activo', 1)
                    ->first();
    }

    /**
     * Get all active technicians for dropdowns
     */
    public function getTecnicos(): array
    {
        return $this->select('id, nombre, apellido')
                    ->where('activo', 1)
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(int $id): void
    {
        $this->update($id, ['ultimo_login' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get DataTable data
     */
    public function getDatatableData(array $params): array
    {
        $builder = $this->db->table('usuarios')
                            ->select('id, nombre, apellido, email, rol, activo, ultimo_login, created_at')
                            ->where('deleted_at IS NULL');

        // Global search
        if (! empty($params['search']['value'])) {
            $search = $params['search']['value'];
            $builder->groupStart()
                    ->like('nombre', $search)
                    ->orLike('apellido', $search)
                    ->orLike('email', $search)
                    ->orLike('rol', $search)
                    ->groupEnd();
        }

        $total    = $this->db->table('usuarios')->where('deleted_at IS NULL')->countAllResults();
        $filtered = $builder->countAllResults(false);

        // Order
        $columns = ['id', 'nombre', 'apellido', 'email', 'rol', 'activo', 'ultimo_login', 'created_at'];
        if (isset($params['order'][0])) {
            $col   = $columns[$params['order'][0]['column']] ?? 'id';
            $dir   = $params['order'][0]['dir'] === 'asc' ? 'ASC' : 'DESC';
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
}
