<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Dashboard</h3>
<?= $this->include('partials/flash') ?>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card card-soft p-3 stat">
      <div class="icon"><i class="bi bi-journal-text"></i></div>
      <div>
        <div class="small text-secondary">Total Courses</div>
        <div class="h4 mb-0"><?= esc($totalCourses ?? 0) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-soft p-3 stat">
      <div class="icon" style="background:#ecfeff;color:#0891b2"><i class="bi bi-people"></i></div>
      <div>
        <div class="small text-secondary">Students</div>
        <div class="h4 mb-0"><?= esc($totalStudents ?? 0) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-soft p-3 stat">
      <div class="icon" style="background:#ecfccb;color:#22C55E"><i class="bi bi-check2-circle"></i></div>
      <div>
        <div class="small text-secondary">Enrollments</div>
        <div class="h4 mb-0"><?= esc($totalEnroll ?? 0) ?></div>
      </div>
    </div>
  </div>
</div>

<!-- Recent lists -->
<div class="row g-3">
  <div class="col-lg-6">
    <div class="card card-soft p-3 h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">Latest Courses</h5>
        <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('admin/courses') ?>">Manage</a>
      </div>
      <?php if (empty($latestCourses)): ?>
        <div class="text-secondary small">Belum ada course.</div>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($latestCourses as $c): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold"><?= esc($c['course_name']) ?></div>
                <div class="small text-secondary"><?= esc($c['credits']) ?> SKS</div>
              </div>
              <a class="btn btn-sm btn-primary" href="<?= site_url('admin/courses/edit/'.$c['course_id']) ?>">Edit</a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card card-soft p-3 h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">Newest Students</h5>
        <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('admin/students') ?>">Manage</a>
      </div>
      <?php if (empty($latestStudents)): ?>
        <div class="text-secondary small">Belum ada student.</div>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($latestStudents as $s): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold"><?= esc($s['full_name']) ?></div>
                <div class="small text-secondary">NIM <?= esc($s['student_id']) ?> • <?= esc($s['entry_year']) ?></div>
              </div>
              <a class="btn btn-sm btn-primary" href="<?= site_url('admin/students/edit/'.$s['student_id']) ?>">Edit</a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
