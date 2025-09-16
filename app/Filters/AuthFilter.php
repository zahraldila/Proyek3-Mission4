<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1) Wajib login
        if (!$session->get('logged_in')) {
            // simpan URL tujuan biar bisa balik habis login
            $session->set('intended', current_url());
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        // 2) Jika butuh role tertentu (contoh: auth:admin)
        if (!empty($arguments)) {
            $allowedRoles = $arguments;                    // bisa lebih dari satu: auth:admin,operator
            $userRole     = $session->get('role');

            if (!in_array($userRole, $allowedRoles, true)) {
                // boleh redirect atau kasih 403
                return redirect()->to('/login')->with('error', 'Akses ditolak (role tidak sesuai).');
                // Atau:
                // return service('response')->setStatusCode(403, 'Forbidden');
            }
        }
        // kalau lolos, lanjut ke controller
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak dipakai
    }
}
