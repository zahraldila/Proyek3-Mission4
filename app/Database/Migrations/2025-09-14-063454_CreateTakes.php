<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTakes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'student_id'  => ['type'=>'CHAR','constraint'=>10],
            'course_id'   => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'enroll_date' => ['type'=>'DATE','null'=>true],
        ]);
        $this->forge->addKey(['student_id','course_id'], true); // composite PK
        $this->forge->addForeignKey('student_id','students','student_id','CASCADE','CASCADE');
        $this->forge->addForeignKey('course_id','courses','course_id','CASCADE','CASCADE');
        $this->forge->createTable('takes');
    }
    public function down(){ $this->forge->dropTable('takes'); }

}
