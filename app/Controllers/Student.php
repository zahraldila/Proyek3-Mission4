<?php

namespace App\Controllers;

class Student extends BaseController {
    public function index()
    {
        $db  = db_connect();
        $uid = (int) session('user_id');

        // ambil NIM student
        $nimRow = $db->table('students')->select('student_id')->where('user_id', $uid)->get()->getRowArray();
        $nim    = $nimRow['student_id'] ?? null;

        // hitungan cepat
        $enrolledCount  = 0;
        $totalCredits   = 0;
        $recentEnrolled = [];

        if ($nim) {
            $enrolledCount = (int) $db->table('takes')->where('student_id', $nim)->countAllResults();

            $totalCredits = (int) $db->table('takes t')
                ->join('courses c', 'c.course_id = t.course_id')
                ->where('t.student_id', $nim)
                ->selectSum('c.credits', 'sum_credits')->get()->getRow('sum_credits');

            $recentEnrolled = $db->table('takes t')
                ->select('c.course_id, c.course_name, c.credits, t.enroll_date')
                ->join('courses c', 'c.course_id = t.course_id')
                ->where('t.student_id', $nim)
                ->orderBy('t.enroll_date','desc')
                ->limit(5)->get()->getResultArray();
        }

        $availableCount = (int) $db->table('courses')->countAllResults();

        return view('student/index', [
            'title'          => 'Student Dashboard',
            'fullName'       => session('full_name'),
            'enrolledCount'  => $enrolledCount,
            'totalCredits'   => $totalCredits,
            'availableCount' => $availableCount,
            'recentEnrolled' => $recentEnrolled,
        ]);
    }
}
