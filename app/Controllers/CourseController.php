<?php namespace App\Controllers;

use App\Models\CourseModel;

class CourseController extends BaseController
{
    public function index()
    {
        $courses = (new CourseModel())->orderBy('course_id','desc')->findAll();
        return view('admin/courses/index', [
            'title'   => 'Manage Courses',
            'courses' => $courses
        ]);
    }

    public function create()
    {
        $model = new CourseModel();

        // gunakan getMethod(true) === 'POST' agar aman dari case-sensitivity
        if ($this->request->getMethod(true) === 'POST') {
            $data = [
                'course_name' => (string) $this->request->getPost('course_name'),
                'credits'     => (int) $this->request->getPost('credits'),
            ];

            // validasi ringan
            if ($data['course_name'] === '' || $data['credits'] < 1 || $data['credits'] > 6) {
                return view('admin/courses/form', [
                    'title'  => 'Add Course',
                    'errors' => ['Isi Course Name dan Credits 1–6.']
                ]);
            }

            if (! $model->insert($data)) {
                return view('admin/courses/form', [
                    'title'  => 'Add Course',
                    'errors' => $model->errors() ?: ['Insert gagal.']
                ]);
            }

            return redirect()->to(site_url('admin/courses'))->with('success', 'Course created.');
        }

        // GET: tampilkan form
        return view('admin/courses/form', ['title' => 'Add Course']);
    }

    public function edit($id)
    {
        $model  = new CourseModel();
        $course = $model->find($id);
        if (! $course) {
            return redirect()->to(site_url('admin/courses'))->with('error', 'Not found.');
        }

        if ($this->request->getMethod(true) === 'POST') {
            $data = [
                'course_name' => (string) $this->request->getPost('course_name'),
                'credits'     => (int) $this->request->getPost('credits'),
            ];

            if ($data['course_name'] === '' || $data['credits'] < 1 || $data['credits'] > 6) {
                return view('admin/courses/form', [
                    'title'  => 'Edit Course',
                    'errors' => ['Isi Course Name dan Credits 1–6.'],
                    'course' => $course
                ]);
            }

            if (! $model->update($id, $data)) {
                return view('admin/courses/form', [
                    'title'  => 'Edit Course',
                    'errors' => $model->errors() ?: ['Update gagal.'],
                    'course' => $course
                ]);
            }

            return redirect()->to(site_url('admin/courses'))->with('success', 'Course updated.');
        }

        // GET: tampilkan form edit
        return view('admin/courses/form', [
            'title'  => 'Edit Course',
            'course' => $course
        ]);
    }

    public function delete($id)
    {
        (new CourseModel())->delete($id);
        return redirect()->to(site_url('admin/courses'))->with('success', 'Course deleted.');
    }
}
