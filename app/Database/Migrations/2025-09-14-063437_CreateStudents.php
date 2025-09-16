<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudents extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'student_id' => ['type'=>'CHAR','constraint'=>9], // NIM
            'entry_year' => ['type'=>'YEAR'],
            'user_id'    => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'unique'=>true],
        ]);
        $this->forge->addKey('student_id', true);
        $this->forge->addForeignKey('user_id','users','user_id','CASCADE','CASCADE');
        $this->forge->createTable('students');
    }
    public function down(){ $this->forge->dropTable('students'); }

}
