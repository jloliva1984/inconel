<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    protected UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Show login page
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        // Already logged in → redirect to dashboard
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', [
            'title' => 'Iniciar Sesión - Inconel Building',
        ]);
    }

    /**
     * Process login
     */
    public function doLogin(): \CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->usuarioModel->findByEmail($email);

        if ($user === null || ! password_verify($password, $user['password'])) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Correo electrónico o contraseña incorrectos.');
        }

        // Set session
        session()->set([
            'user_id'     => $user['id'],
            'user_nombre' => $user['nombre'],
            'user_email'  => $user['email'],
            'user_role'   => $user['rol'],
            'logged_in'   => true,
        ]);

        // Update last login
        $this->usuarioModel->updateLastLogin($user['id']);

        return redirect()->to('/dashboard')->with('success', '¡Bienvenido, ' . $user['nombre'] . '!');
    }

    /**
     * Logout
     */
    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Sesión cerrada correctamente.');
    }
}
