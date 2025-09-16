<?php namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index() {
        return view('auth/login'); // tampilkan form
    }

    public function attempt() {
        $username = trim($this->request->getPost('username') ?? '');
        $password = (string) $this->request->getPost('password');

        $user = (new UserModel())->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        // set session
        session()->set([
            'user_id'   => $user['user_id'],
            'username'  => $user['username'],
            'role'      => $user['role'],
            'full_name' => $user['full_name'],
            'logged_in' => true,
        ]);

        $intended = session()->get('intended') ?? null;
        session()->remove('intended');

        if ($intended) {
            return redirect()->to($intended);
        }
        return redirect()->to($user['role'] === 'admin' ? '/admin' : '/student');
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/login');
    }
}
