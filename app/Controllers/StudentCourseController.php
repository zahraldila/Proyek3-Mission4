<?php namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\StudentModel;

class StudentCourseController extends BaseController
{
    private function currentNIM(): ?string
    {
        $userId = (int) session('user_id');
        if (!$userId) return null;
        $row = (new StudentModel())->where('user_id', $userId)->first();
        return $row['student_id'] ?? null;
    }

    // list semua courses + status enrolled/belum
    public function index()
    {
        $nim = $this->currentNIM();
        if (!$nim) return redirect()->to('/login')->with('error','Data student tidak ditemukan.');

        $db = db_connect();
        $courses = $db->table('courses c')
            ->select("c.course_id, c.course_name, c.credits,
                      (CASE WHEN t.student_id IS NULL THEN 0 ELSE 1 END) AS enrolled")
            ->join('takes t', "t.course_id = c.course_id AND t.student_id = ".$db->escape($nim), 'left')
            ->orderBy('c.course_id','asc')
            ->get()->getResultArray();

        return view('student/courses/index', [
            'title'   => 'Courses',
            'courses' => $courses
        ]);
    }

    // aksi enroll
    public function enroll($courseId)
    {
        $nim = $this->currentNIM();
        if (!$nim) return redirect()->to('/login')->with('error','Data student tidak ditemukan.');

        $db = db_connect();

        // cek sudah ada belum
        $exists = $db->table('takes')
            ->where(['student_id' => $nim, 'course_id' => (int)$courseId])
            ->get()->getFirstRow();

        if ($exists) {
            return redirect()->to(site_url('student/courses'))->with('success','Sudah ter-enroll.');
        }

        // insert baru
        $db->table('takes')->insert([
            'student_id'  => $nim,
            'course_id'   => (int)$courseId,
            'enroll_date' => date('Y-m-d'),
        ]);

        return redirect()->to(site_url('student/my-courses'))->with('success','Enroll berhasil.');
    }

    // daftar course yang diambil student ini
    public function myCourses()
    {
        $nim = $this->currentNIM();
        if (!$nim) return redirect()->to('/login')->with('error','Data student tidak ditemukan.');

        $db = db_connect();
        $rows = $db->table('takes t')
            ->select('c.course_id, c.course_name, c.credits, t.enroll_date')
            ->join('courses c', 'c.course_id = t.course_id')
            ->where('t.student_id', $nim)
            ->orderBy('t.enroll_date','desc')
            ->get()->getResultArray();

        return view('student/courses/my_courses', [
            'title'=>'My Courses',
            'items'=>$rows
        ]);
    }

    public function unenroll($courseId)
    {
        $nim = $this->currentNIM();
        if (!$nim) return redirect()->to('/login')->with('error','Data student tidak ditemukan.');

        db_connect()->table('takes')
            ->where(['student_id'=>$nim, 'course_id'=>(int)$courseId])
            ->delete();

        return redirect()->to(site_url('student/my-courses'))->with('success','Unenroll berhasil.');
    }

}
