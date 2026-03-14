<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (! $session->has('user_id')) {
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON(['error' => 'Sesión expirada. Por favor recargue la página.']);
            }
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión para acceder.');
        }

        // Check role if arguments are provided
        if ($arguments !== null) {
            $userRole = $session->get('user_role');

            if (! in_array($userRole, $arguments, true)) {
                if ($request->isAJAX()) {
                    return service('response')
                        ->setStatusCode(403)
                        ->setJSON(['error' => 'No tiene permisos para acceder a esta sección.']);
                }
                return redirect()->to('/dashboard')->with('error', 'No tiene permisos para acceder a esta sección.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after
    }
}
