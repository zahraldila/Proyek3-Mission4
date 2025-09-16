<?php

namespace App\Controllers;

class Admin extends BaseController {
    public function index()
{
    $db = db_connect();

    $totalCourses  = (int) $db->table('courses')->countAllResults();
    $totalStudents = (int) $db->table('students')->countAllResults();
    $totalEnroll   = (int) $db->table('takes')->countAllResults();

    $latestCourses = $db->table('courses')->orderBy('course_id','desc')->limit(5)->get()->getResultArray();

    $latestStudents = $db->table('students s')
        ->select('s.student_id, s.entry_year, u.full_name')
        ->join('users u', 'u.user_id = s.user_id')
        ->orderBy('s.student_id','desc')
        ->limit(5)->get()->getResultArray();

    return view('admin/index', [
        'title'          => 'Admin Dashboard',
        'totalCourses'   => $totalCourses,
        'totalStudents'  => $totalStudents,
        'totalEnroll'    => $totalEnroll,
        'latestCourses'  => $latestCourses,
        'latestStudents' => $latestStudents,
    ]);
}
}
