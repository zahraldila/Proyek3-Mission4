<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id'       => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'username'      => ['type'=>'VARCHAR','constraint'=>50,'unique'=>true],
            'password_hash' => ['type'=>'VARCHAR','constraint'=>255], // simpan hasil password_hash()
            'role'          => ['type'=>'ENUM','constraint'=>['admin','student'],'default'=>'student'],
            'full_name'     => ['type'=>'VARCHAR','constraint'=>100],
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable('users');
    }
    public function down(){ $this->forge->dropTable('users'); }

}
