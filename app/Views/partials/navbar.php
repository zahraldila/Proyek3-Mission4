<?php
$role     = session('role');
$loggedIn = session('logged_in');
$uri1     = service('uri')->getSegment(1); // segmen pertama url (admin/student/...)
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="<?= site_url('/') ?>">Akademik</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($loggedIn && $role === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link <?= $uri1==='admin'?'active':'' ?>" href="<?= site_url('admin') ?>">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $uri1==='courses'?'active':'' ?>" href="<?= site_url('admin/courses') ?>">Manage Courses</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $uri1==='students'?'active':'' ?>" href="<?= site_url('admin/students') ?>">Manage Students</a>
          </li>
        <?php elseif ($loggedIn && $role === 'student'): ?>
          <li class="nav-item">
            <a class="nav-link <?= $uri1===''?'active':'' ?>" href="<?= site_url('/') ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $uri1==='student'?'active':'' ?>" href="<?= site_url('student') ?>">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $uri1==='courses'?'active':'' ?>" href="<?= site_url('student/courses') ?>">Courses</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $uri1==='my-courses'?'active':'' ?>" href="<?= site_url('student/my-courses') ?>">My Courses</a>
          </li>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav">
        <?php if ($loggedIn): ?>
          <li class="nav-item"><span class="navbar-text me-3">Hi, <?= esc(session('full_name')) ?></span></li>
          <li class="nav-item"><a class="btn btn-outline-danger" href="<?= site_url('logout') ?>">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-primary" href="<?= site_url('login') ?>">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
