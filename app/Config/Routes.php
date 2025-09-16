<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Halaman publik
$routes->get('/',       'Home::index');
$routes->get('login',   'Auth::index');
$routes->post('login',  'Auth::attempt');
$routes->get('logout',  'Auth::logout');

// AREA ADMIN (proteksi role=admin)
$routes->group('admin', ['filter' => 'auth:admin'], function($r){

    // Dashboard
    $r->get('/', 'Admin::index');

    // Courses (CRUD)
    $r->get('courses',                           'CourseController::index');              // list
    $r->match(['get','post'], 'courses/create',  'CourseController::create');             // add
    $r->match(['get','post'], 'courses/edit/(:num)', 'CourseController::edit/$1');        // edit (ID numerik)
    $r->post('courses/delete/(:num)',            'CourseController::delete/$1');          // delete via POST
   
    // app/Config/Routes.php (dalam $routes->group('admin', ...))
    $r->get('students',                          'StudentAdminController::index');
    $r->match(['get','post'], 'students/create', 'StudentAdminController::create');
    $r->match(['get','post'], 'students/edit/(:segment)', 'StudentAdminController::edit/$1'); // NIM string
    $r->post('students/delete/(:segment)',       'StudentAdminController::delete/$1');

});
 
// AREA STUDENT (proteksi role=student)
$routes->group('student', ['filter' => 'auth:student'], function($r){
    $r->get('/', 'Student::index');

    // Courses for student
    $r->get('courses',              'StudentCourseController::index');   // list semua + tombol enroll
    $r->post('courses/enroll/(:num)','StudentCourseController::enroll/$1'); // enroll by course_id
    $r->get('my-courses',           'StudentCourseController::myCourses');  // daftar yang diambil
    $r->post('courses/unenroll/(:num)', 'StudentCourseController::unenroll/$1');

});

