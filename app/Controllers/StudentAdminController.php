<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StudentModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class StudentAdminController extends BaseController
{
    public function index()
    {
        // join sederhana users<-students
        $db = db_connect();
        $rows = $db->table('students s')
            ->select('s.student_id, s.entry_year, u.user_id, u.username, u.full_name')
            ->join('users u', 'u.user_id = s.user_id')
            ->orderBy('s.student_id','asc')->get()->getResultArray();

        return view('admin/students/index', [
            'title' => 'Manage Students',
            'students' => $rows
        ]);
    }

    public function create()
    {
        if ($this->request->getMethod(true) === 'POST') {
            $nim        = trim((string)$this->request->getPost('student_id')); // NIM
            $entryYear  = (int)$this->request->getPost('entry_year');
            $username   = trim((string)$this->request->getPost('username'));
            $fullName   = trim((string)$this->request->getPost('full_name'));
            $password   = (string)$this->request->getPost('password');

            // validasi ringan
            $errs = [];
            if ($nim === '' || strlen($nim) < 5) $errs[] = 'NIM wajib & masuk akal.';
            if ($entryYear < 2000 || $entryYear > 2100) $errs[] = 'Entry year tidak valid.';
            if ($username === '' || strlen($username) < 3) $errs[] = 'Username minimal 3 karakter.';
            if ($fullName === '') $errs[] = 'Nama lengkap wajib.';
            if (strlen($password) < 6) $errs[] = 'Password minimal 6 karakter.';
            if ($errs) return view('admin/students/form', ['title'=>'Add Student','errors'=>$errs]);

            $db = db_connect();
            $db->transStart();
            try {
                // 1) buat user role student
                $userId = (new UserModel())->insert([
                    'username'      => $username,
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                    'role'          => 'student',
                    'full_name'     => $fullName,
                ], true);

                // 2) buat student yang mengait ke user
                (new StudentModel())->insert([
                    'student_id' => $nim,
                    'entry_year' => $entryYear,
                    'user_id'    => $userId,
                ]);

                $db->transComplete();
                if ($db->transStatus() === false) throw new DatabaseException('Transaksi gagal');

                return redirect()->to(site_url('admin/students'))->with('success','Student created.');
            } catch (\Throwable $e) {
                $db->transRollback();
                // kemungkinan username sudah ada, atau NIM sudah ada (PK)
                return view('admin/students/form', [
                    'title'=>'Add Student',
                    'errors'=> ['Gagal menyimpan: '.$e->getMessage()]
                ]);
            }
        }

        return view('admin/students/form', ['title'=>'Add Student']);
    }

    public function edit($nim)
    {
        $student = (new StudentModel())->where('student_id',$nim)->first();
        if (! $student) return redirect()->to(site_url('admin/students'))->with('error','Student not found.');

        // ambil user terkait
        $user = (new UserModel())->find($student['user_id']);

        if ($this->request->getMethod(true) === 'POST') {
            $entryYear  = (int)$this->request->getPost('entry_year');
            $username   = trim((string)$this->request->getPost('username'));
            $fullName   = trim((string)$this->request->getPost('full_name'));
            $password   = (string)$this->request->getPost('password'); // boleh kosong = tidak diubah

            $errs = [];
            if ($entryYear < 2000 || $entryYear > 2100) $errs[] = 'Entry year tidak valid.';
            if ($username === '' || strlen($username) < 3) $errs[] = 'Username minimal 3 karakter.';
            if ($fullName === '') $errs[] = 'Nama lengkap wajib.';
            if ($errs) return view('admin/students/form', ['title'=>'Edit Student','errors'=>$errs,'student'=>$student,'user'=>$user]);

            $db = db_connect();
            $db->transStart();
            try {
                // update user
                $userData = [
                    'username'  => $username,
                    'full_name' => $fullName,
                ];
                if ($password !== '') {
                    $userData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
                }
                (new UserModel())->update($user['user_id'], $userData);

                // update entry year (nim sebagai PK tak diubah)
                (new StudentModel())->update($nim, ['entry_year'=>$entryYear]);

                $db->transComplete();
                if ($db->transStatus() === false) throw new DatabaseException('Transaksi gagal');

                return redirect()->to(site_url('admin/students'))->with('success','Student updated.');
            } catch (\Throwable $e) {
                $db->transRollback();
                return view('admin/students/form', [
                    'title'=>'Edit Student','errors'=>['Gagal menyimpan: '.$e->getMessage()],
                    'student'=>$student,'user'=>$user
                ]);
            }
        }

        return view('admin/students/form', [
            'title'=>'Edit Student',
            'student'=>$student,
            'user'=>$user
        ]);
    }

    public function delete($nim)
    {
        // hapus student + (opsional) user terkait
        $student = (new StudentModel())->find($nim);
        if ($student) {
            $db = db_connect();
            $db->transStart();
            try {
                // hapus student dulu
                (new StudentModel())->delete($nim);

                // opsional: hapus user (hati2 kalau suatu saat 1 user bisa punya lebih dari 1 peran)
                (new UserModel())->delete($student['user_id']);

                $db->transComplete();
                if ($db->transStatus() === false) throw new \Exception('Transaksi gagal');
            } catch (\Throwable $e) {
                $db->transRollback();
                return redirect()->to(site_url('admin/students'))->with('error','Gagal menghapus: '.$e->getMessage());
            }
        }
        return redirect()->to(site_url('admin/students'))->with('success','Student deleted.');
    }
}
