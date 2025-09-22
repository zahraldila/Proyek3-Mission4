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

  <!-- Search -->
  <form class="mb-3" onsubmit="event.preventDefault(); filterCards(this.q.value)">
    <div class="input-group">
      <span class="input-group-text" style="border-radius:999px 0 0 999px;"><i class="bi bi-search"></i></span>
      <input name="q" class="form-control" style="border-radius:0 999px 999px 0;" placeholder="Cari course…">
      <button type="button" class="btn btn-outline-secondary ms-2" onclick="this.form.q.value=''; filterCards('')">Reset</button>
    </div>
  </form>

  <!-- Enroll (Checklist) -->
  <section class="card card-soft p-3 mb-3">
    <h5 class="mb-2">Enroll (Checklist)</h5>
    <div id="courseList" class="d-flex flex-column gap-2"></div>
    <div class="mt-2"><strong>Total SKS:</strong> <span id="totalSks">0</span></div>
    <div id="enrollMsg" class="mt-2 small"></div>
    <button id="btnEnroll" class="btn btn-primary mt-2" disabled>Enroll Selected</button>
  </section>

  <!-- Grid kartu course -->
  <div class="row g-3" id="courseGrid">
    <?php foreach ($courses as $c): ?>
      <div class="col-md-6 col-lg-4 course-card"
           data-course-id="<?= esc($c['course_id']) ?>"
           data-name="<?= esc($c['course_name']) ?>"
           data-credits="<?= esc($c['credits']) ?>">
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
    // filter sederhana di sisi klien
    function filterCards(q){
      const term = (q||'').toLowerCase().trim();
      document.querySelectorAll('#courseGrid .course-card').forEach(card=>{
        const name = card.querySelector('.course-title')?.textContent.toLowerCase() || '';
        card.style.display = (!term || name.includes(term)) ? '' : 'none';
      });
    }
  </script>

<?php endif; ?>

<!-- Inject data dari server -> JS (array of objects) -->
<script>
  window.appData = {
    courses: <?= json_encode($courses ?? []) ?>,
    students: <?= json_encode($students ?? []) ?>,
  };
</script>

<!-- DOM render checklist + enroll tanpa refresh -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const list   = document.getElementById('courseList');
  const total  = document.getElementById('totalSks');
  const btn    = document.getElementById('btnEnroll');
  const msg    = document.getElementById('enrollMsg');

  // 1) Render checkbox dari data + simpan SKS di data-attr
  (window.appData.courses || []).forEach(c => {
    const id   = String(c.course_id);
    const name = c.course_name;
    const sks  = parseInt(c.credits, 10) || 0;
    const isEnrolled = String(c.enrolled || '0') === '1';

    const row = document.createElement('div');
    // flex + gap + padding; putih biar kontras
    row.className = 'd-flex align-items-center gap-2 border rounded p-2 bg-white';

    const input = document.createElement('input');
    input.type = 'checkbox';
    input.className = 'form-check-input me-2';  
    input.id = `chk-${id}`;
    input.value = id;
    input.dataset.sks = sks;
    if (isEnrolled) input.disabled = true;

    const label = document.createElement('label');
    label.className = 'mb-0 flex-grow-1';  
    label.htmlFor = input.id;
    label.innerHTML = `${name} <small class="text-muted">(${sks} SKS)</small>`;

    row.appendChild(input);
    row.appendChild(label);
    list.appendChild(row);
  });

  // 2) Hitung total & toggle tombol
  function refreshTotal() {
    const checked = list.querySelectorAll('input[type="checkbox"]:checked');
    let sum = 0;
    checked.forEach(cb => sum += parseInt(cb.dataset.sks, 10) || 0);
    total.textContent = sum;
    btn.disabled = checked.length === 0;
  }
  list.addEventListener('change', e => {
    if (e.target.matches('input[type="checkbox"]')) refreshTotal();
  });
  refreshTotal();

  // helper: ubah kartu jadi "Enrolled" (tanpa refresh)
  function markCardAsEnrolled(courseId) {
    const card = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
    if (!card) return;
    const body = card.querySelector('.card-body');
    if (!body) return;

    // buang form enroll lama
    const oldForm = body.querySelector('form');
    if (oldForm) oldForm.remove();

    // tambahkan chip + tombol My Courses kalau belum ada
    if (!card.querySelector('.chip-ok')) {
      const metaRow = document.createElement('div');
      metaRow.className = 'mt-auto d-flex justify-content-between align-items-center';
      metaRow.innerHTML = `
        <span class="chip chip-ok"><i class="bi bi-check2-circle"></i> Enrolled</span>
        <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('student/my-courses') ?>">My Courses</a>
      `;
      body.appendChild(metaRow);
    }
  }

  // 3) Enroll batch via fetch (tanpa refresh)
  btn.addEventListener('click', async () => {
    const selected = [...list.querySelectorAll('input[type="checkbox"]:checked')];
    if (selected.length === 0) return;

    btn.disabled = true;
    msg.className = 'mt-2 small text-secondary';
    msg.textContent = 'Menyimpan...';

    const csrfName = '<?= csrf_token() ?>';
    const csrfHash = '<?= csrf_hash() ?>';
    const baseUrl  = '<?= site_url('student/courses/enroll') ?>';

    const succeeded = [];
    const failed    = [];

    for (const cb of selected) {
      const courseId = cb.value;
      const form = new FormData();
      form.append(csrfName, csrfHash);

      try {
        const resp = await fetch(`${baseUrl}/${courseId}`, {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          body: form,
          redirect: 'follow'
        });
        const ok = resp.ok || resp.redirected || resp.status === 204;
        if (ok) {
          succeeded.push(courseId);
          cb.checked = false;
          cb.disabled = true;
          markCardAsEnrolled(courseId);
        } else {
          failed.push(courseId);
        }
      } catch (err) {
        failed.push(courseId);
      }
    }

    // update total & tombol
    refreshTotal();

    if (failed.length === 0) {
      msg.className = 'mt-2 small text-success';
      msg.textContent = `Enroll berhasil: ${succeeded.join(', ')}`;
    } else if (succeeded.length === 0) {
      msg.className = 'mt-2 small text-danger';
      msg.textContent = `Enroll gagal: ${failed.join(', ')}`;
      btn.disabled = false;
    } else {
      msg.className = 'mt-2 small text-warning';
      msg.textContent = `Sebagian berhasil: ${succeeded.join(', ')}, gagal: ${failed.join(', ')}`;
      btn.disabled = false;
    }
  });
});
</script>

<?= $this->endSection() ?>
