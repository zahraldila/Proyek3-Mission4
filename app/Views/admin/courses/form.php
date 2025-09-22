<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
  $isEdit = isset($course);
  $action = $isEdit
    ? site_url('admin/courses/edit/'.$course['course_id'])
    : site_url('admin/courses/create');
  $errs = $errors ?? session()->getFlashdata('errors') ?? [];
  if (!is_array($errs)) $errs = [];
  $val = fn($k,$d='') => old($k, $course[$k] ?? $d);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0"><?= $isEdit ? 'Edit Course' : 'Add Course' ?></h3>
  <a class="btn btn-secondary" href="<?= site_url('admin/courses') ?>">Back</a>
</div>

<?php if ($errs): ?>
  <div class="alert alert-danger"><ul class="mb-0">
    <?php foreach($errs as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
  </ul></div>
<?php endif; ?>

<div class="card card-soft p-3">
  <form id="courseForm" method="post" action="<?= $action ?>" novalidate>
    <?= csrf_field() ?>

    <div class="mb-3">
      <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
      <input
        id="course_name"
        type="text"
        name="course_name"
        class="form-control"
        value="<?= esc($val('course_name')) ?>"
        placeholder="mis. Grafika Komputer"
        autocomplete="off"
        autofocus>
      <div class="invalid-feedback">Nama mata kuliah wajib diisi (min. 3 karakter).</div>
    </div>

    <div class="mb-3">
      <label for="credits" class="form-label">Credits (1–6) <span class="text-danger">*</span></label>
      <input
        id="credits"
        type="number"
        name="credits"
        min="1" max="6"
        class="form-control"
        value="<?= esc($val('credits',3)) ?>">
      <div class="invalid-feedback">SKS harus angka 1–6.</div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary"><?= $isEdit ? 'Update' : 'Save' ?></button>
      <a class="btn btn-outline-secondary" href="<?= site_url('admin/courses') ?>">Cancel</a>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form     = document.getElementById('courseForm');
  const nameEl   = document.getElementById('course_name');
  const creditEl = document.getElementById('credits');

  function setErr(el, msg){
    el.classList.add('is-invalid');
    el.setAttribute('aria-invalid','true');
    const fb = el.nextElementSibling;
    if (fb && fb.classList.contains('invalid-feedback')) fb.textContent = msg;
  }
  function clearErr(el){
    el.classList.remove('is-invalid');
    el.removeAttribute('aria-invalid');
  }

  function validateName(){
    const v = (nameEl.value || '').trim();
    if (!v) { setErr(nameEl, 'Nama mata kuliah wajib diisi.'); return false; }
    if (v.length < 3) { setErr(nameEl, 'Minimal 3 karakter.'); return false; }
    clearErr(nameEl); return true;
  }

  function validateCredits(){
    const raw = creditEl.value;
    const n = Number(raw);
    if (raw === '') { setErr(creditEl, 'SKS wajib diisi.'); return false; }
    if (!Number.isInteger(n) || n < 1 || n > 6) { setErr(creditEl, 'SKS harus angka 1–6.'); return false; }
    clearErr(creditEl); return true;
  }

  // live feedback
  nameEl.addEventListener('input', validateName);
  creditEl.addEventListener('input', validateCredits);
  nameEl.addEventListener('blur', validateName);
  creditEl.addEventListener('blur', validateCredits);

  // block submit jika invalid
  form.addEventListener('submit', (e) => {
    const ok1 = validateName();
    const ok2 = validateCredits();
    if (!ok1 || !ok2) e.preventDefault();
  });
});
</script>

<?= $this->endSection() ?>
