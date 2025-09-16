<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')->insertBatch([
            [
              'username'=>'admin',
              'password_hash'=>password_hash('admin123', PASSWORD_BCRYPT),
              'role'=>'admin','full_name'=>'Site Admin'
            ],
            [
              'username'=>'zahra',
              'password_hash'=>password_hash('zahra123', PASSWORD_BCRYPT),
              'role'=>'student','full_name'=>'Zahra Aldila'
            ],
        ]);

        // map user 'zahra' ke NIM
        $user = $this->db->table('users')->where('username','zahra')->get()->getRowArray();
        $this->db->table('students')->insert([
            'student_id'=>'241511094', 'entry_year'=>2024, 'user_id'=>$user['user_id']
        ]);
    }
}

