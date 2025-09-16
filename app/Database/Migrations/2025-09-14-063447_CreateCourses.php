<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourses extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'course_id'   => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'course_name' => ['type'=>'VARCHAR','constraint'=>100],
            'credits'     => ['type'=>'TINYINT','unsigned'=>true],
        ]);
        $this->forge->addKey('course_id', true);
        $this->forge->createTable('courses');
    }
    public function down(){ $this->forge->dropTable('courses'); }

}
