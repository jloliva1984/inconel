<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Users extends BaseController
{
    protected UsuarioModel $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
    }

    public function index(): string
    {
        return view('users/index', [
            'title' => 'Usuarios - Inconel Building',
        ]);
    }

    /**
     * DataTables AJAX endpoint
     */
    public function datatable(): \CodeIgniter\HTTP\ResponseInterface
    {
        $params = $this->request->getGet();
        $data   = $this->model->getDatatableData($params);

        // Add action buttons
        foreach ($data['data'] as &$row) {
            $row['acciones'] = $this->buildActions($row);
        }

        return $this->response->setJSON($data);
    }

    public function crear(): string
    {
        return view('users/form', [
            'title'   => 'Crear Usuario - Inconel Building',
            'usuario' => null,
            'action'  => site_url('usuarios/guardar'),
        ]);
    }

    public function guardar(): \CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'nombre'            => 'required|min_length[2]|max_length[100]',
            'apellido'          => 'required|min_length[2]|max_length[100]',
            'email'             => 'required|valid_email|is_unique[usuarios.email]',
            'password'          => 'required|min_length[6]',
            'password_confirm'  => 'required|matches[password]',
            'rol'               => 'required|in_list[admin,tecnico]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'rol'      => $this->request->getPost('rol'),
            'activo'   => (int) $this->request->getPost('activo', FILTER_SANITIZE_NUMBER_INT) ?? 1,
        ];

        $this->model->insert($data);

        return redirect()->to('/usuarios')->with('success', 'Usuario creado correctamente.');
    }

    public function editar(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $usuario = $this->model->find($id);

        if ($usuario === null) {
            return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado.');
        }

        return view('users/form', [
            'title'   => 'Editar Usuario - Inconel Building',
            'usuario' => $usuario,
            'action'  => site_url('usuarios/actualizar/' . $id),
        ]);
    }

    public function actualizar(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $usuario = $this->model->find($id);

        if ($usuario === null) {
            return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado.');
        }

        $rules = [
            'nombre'   => 'required|min_length[2]|max_length[100]',
            'apellido' => 'required|min_length[2]|max_length[100]',
            'email'    => "required|valid_email|is_unique[usuarios.email,id,{$id}]",
            'rol'      => 'required|in_list[admin,tecnico]',
        ];

        // Only validate password if provided
        $newPassword = $this->request->getPost('password');
        if (! empty($newPassword)) {
            $rules['password']         = 'min_length[6]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email'    => $this->request->getPost('email'),
            'rol'      => $this->request->getPost('rol'),
            'activo'   => (int) ($this->request->getPost('activo') ?? 1),
        ];

        if (! empty($newPassword)) {
            $data['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $this->model->update($id, $data);

        return redirect()->to('/usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    public function eliminar(int $id): \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\ResponseInterface
    {
        // Prevent deleting own account
        if ($id === (int) session()->get('user_id')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'No puede eliminar su propia cuenta.']);
            }
            return redirect()->to('/usuarios')->with('error', 'No puede eliminar su propia cuenta.');
        }

        $usuario = $this->model->find($id);

        if ($usuario === null) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Usuario no encontrado.']);
            }
            return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado.');
        }

        $this->model->delete($id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario eliminado correctamente.']);
        }

        return redirect()->to('/usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    private function buildActions(array $row): string
    {
        $editUrl   = site_url('usuarios/editar/' . $row['id']);
        $deleteUrl = site_url('usuarios/eliminar/' . $row['id']);

        return <<<HTML
        <div class="btn-group btn-group-sm" role="group">
            <a href="{$editUrl}" class="btn btn-warning btn-sm" title="Editar">
                <i class="fas fa-edit"></i>
            </a>
            <button type="button" class="btn btn-danger btn-sm btn-delete"
                    data-id="{$row['id']}" data-url="{$deleteUrl}" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        HTML;
    }
}
