<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
  $name           = esc($fullName ?? (session('full_name') ?? 'Student'));
  $enrolledCount  = (int)($enrolledCount ?? 0);
  $totalCredits   = (int)($totalCredits ?? 0);
  $availableCount = (int)($availableCount ?? 0);
?>

<!-- Header -->
<div class="d-flex flex-wrap justify-content-between align-items-end mb-3">
  <div>
    <h3 class="mb-1">Student Dashboard</h3>
    <div class="text-secondary">Halo, <?= $name ?>.</div>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-secondary" href="<?= site_url('student/my-courses') ?>">
      <i class="bi bi-bookmark-check me-1"></i>My Courses
    </a>
    <a class="btn btn-primary" href="<?= site_url('student/courses') ?>">
      <i class="bi bi-grid me-1"></i>Browse Courses
    </a>
  </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card card-soft p-3 stat">
      <div class="icon"><i class="bi bi-bookmark-check"></i></div>
      <div>
        <div class="small text-secondary">Enrolled Courses</div>
        <div class="h4 mb-0"><?= $enrolledCount ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-soft p-3 stat">
      <div class="icon" style="background:#ecfeff;color:#0891b2"><i class="bi bi-123"></i></div>
      <div>
        <div class="small text-secondary">Total Credits</div>
        <div class="h4 mb-0"><?= $totalCredits ?> SKS</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-soft p-3 stat">
      <div class="icon" style="background:#fef3c7;color:#b45309"><i class="bi bi-grid"></i></div>
      <div>
        <div class="small text-secondary">Available Courses</div>
        <div class="h4 mb-0"><?= $availableCount ?></div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <!-- Recently Enrolled -->
  <div class="col-lg-7">
    <div class="card card-soft p-3 h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">Recently Enrolled</h5>
        <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('student/my-courses') ?>">View all</a>
      </div>

      <?php if (empty($recentEnrolled ?? [])): ?>
        <div class="text-secondary small">Belum ada course yang diambil.</div>
      <?php else: ?>
        <ul class="list-group list-group-flush">
        <?php foreach ($recentEnrolled as $it): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold"><?= esc($it['course_name']) ?></div>
              <div class="small text-secondary">
                <?= esc($it['credits']) ?> SKS • <?= esc($it['enroll_date']) ?>
              </div>
            </div>

            <form method="post"
                  action="<?= site_url('student/courses/unenroll/'.(int)$it['course_id']) ?>"
                  onsubmit="return confirm('Unenroll dari course ini?')"
                  class="d-inline">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-outline-danger">Unenroll</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
  </div>

  <!-- Quick Actions / Tips -->
  <div class="col-lg-5">
    <div class="card card-soft p-3 h-100">
      <h5 class="mb-3">Quick Actions</h5>
      <div class="d-grid gap-2">
        <a class="btn btn-enroll" href="<?= site_url('student/courses') ?>">
          <i class="bi bi-plus-lg me-1"></i>Enroll New Course
        </a>
        <a class="btn btn-outline-secondary" href="<?= site_url('student/my-courses') ?>">
          <i class="bi bi-list-check me-1"></i>See My Courses
        </a>
      </div>

      <hr class="my-3">
      <div class="small text-secondary">
        Tips: kamu bisa mencari mata kuliah di halaman <b>Courses</b> lewat kolom pencarian, lalu klik
        <span class="chip chip-ok">Enroll</span> untuk menambahkannya.
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
