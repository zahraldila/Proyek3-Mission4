<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Courses</h3>
  <a class="btn btn-outline-secondary" href="<?= site_url('student/my-courses') ?>">
    <i class="bi bi-bookmark-check me-1"></i>My Courses
  </a>
</div>

<?= $this->include('partials/flash') ?>

<?php if (empty($courses)): ?>
  <div class="card card-soft p-4 text-center text-secondary">
    <i class="bi bi-grid" style="font-size:2rem"></i>
    <p class="mt-2 mb-0">Belum ada course tersedia.</p>
  </div>
<?php else: ?>

  <!-- Search pill -->
  <form class="mb-3" onsubmit="event.preventDefault(); filterCards(this.q.value)">
    <div class="input-group">
      <span class="input-group-text" style="border-radius:999px 0 0 999px;"><i class="bi bi-search"></i></span>
      <input name="q" class="form-control" style="border-radius:0 999px 999px 0;" placeholder="Cari course…">
      <button type="button" class="btn btn-outline-secondary ms-2" onclick="this.form.q.value=''; filterCards('')">Reset</button>
    </div>
  </form>

  <div class="row g-3" id="courseGrid">
    <?php foreach ($courses as $c): ?>
      <div class="col-md-6 col-lg-4 course-card">
        <div class="card card-soft h-100">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-1">
              <div class="course-title"><?= esc($c['course_name']) ?></div>
              <span class="badge badge-sks"><?= esc($c['credits']) ?> SKS</span>
            </div>
            <div class="course-meta mb-3">ID: <?= esc($c['course_id']) ?></div>

            <?php if (!empty($c['enrolled'])): ?>
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="chip chip-ok"><i class="bi bi-check2-circle"></i> Enrolled</span>
                <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('student/my-courses') ?>">My Courses</a>
              </div>
            <?php else: ?>
              <form method="post" action="<?= site_url('student/courses/enroll/'.$c['course_id']) ?>" class="mt-auto">
                <?= csrf_field() ?>
                <button class="btn btn-enroll w-100">
                  <i class="bi bi-plus-lg me-1"></i>Enroll
                </button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <script>
    // filter sederhana di sisi klien (tanpa ubah controller)
    function filterCards(q){
      const term = (q||'').toLowerCase().trim();
      document.querySelectorAll('#courseGrid .course-card').forEach(card=>{
        const name = card.querySelector('.course-title')?.textContent.toLowerCase() || '';
        card.style.display = (!term || name.includes(term)) ? '' : 'none';
      });
    }
  </script>

<?php endif; ?>

<?= $this->endSection() ?>
