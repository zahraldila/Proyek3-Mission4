<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
  // --- DEFAULTS BIAR GA ERROR DI CREATE ---
  $student = $student ?? [];
  $user    = $user ?? [];

  $isEdit = isset($student['student_id']);
  $action = $isEdit
    ? site_url('admin/students/edit/'.$student['student_id'])
    : site_url('admin/students/create');

  $errs = $errors ?? session()->getFlashdata('errors') ?? [];
  if (!is_array($errs)) $errs = [];

  $val = function(string $key, $fallback='') use ($isEdit, $student, $user) {
    if ($isEdit) {
      if ($key === 'username' || $key === 'full_name') return old($key, $user[$key] ?? '');
      return old($key, $student[$key] ?? $fallback);
    }
    return old($key, $fallback);
  };
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0"><?= $isEdit ? 'Edit Student' : 'Add Student' ?></h3>
  <a class="btn btn-secondary" href="<?= site_url('admin/students') ?>">Back</a>
</div>

<?php if ($errs): ?>
  <div class="alert alert-danger"><ul class="mb-0">
    <?php foreach($errs as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
  </ul></div>
<?php endif; ?>

<div class="card card-soft p-3">
  <form id="studentForm" method="post" action="<?= $action ?>" novalidate>
    <?= csrf_field() ?>

    <?php if(!$isEdit): ?>
      <div class="mb-3">
        <label for="student_id" class="form-label">NIM <span class="text-danger">*</span></label>
        <input id="student_id" name="student_id" class="form-control" maxlength="20"
               value="<?= esc($val('student_id')) ?>" placeholder="mis. 221234567" autocomplete="off">
        <div class="invalid-feedback">NIM wajib angka (min. 6 digit).</div>
      </div>
    <?php else: ?>
      <div class="mb-3">
        <label class="form-label">NIM</label>
        <input class="form-control" value="<?= esc($student['student_id']) ?>" disabled>
      </div>
    <?php endif; ?>

    <div class="mb-3">
      <label for="entry_year" class="form-label">Entry Year <span class="text-danger">*</span></label>
      <input id="entry_year" type="number" name="entry_year" min="2000" max="2100" class="form-control"
             value="<?= esc($val('entry_year', date('Y'))) ?>">
      <div class="invalid-feedback">Tahun masuk 2000–2100.</div>
    </div>

    <div class="mb-3">
      <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
      <input id="full_name" name="full_name" class="form-control"
             value="<?= esc($val('full_name')) ?>" placeholder="Nama lengkap">
      <div class="invalid-feedback">Nama lengkap minimal 3 karakter.</div>
    </div>

    <div class="mb-3">
      <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
      <input id="username" name="username" class="form-control"
             value="<?= esc($val('username')) ?>" placeholder="username login" autocomplete="off">
      <div class="invalid-feedback">Username 3–20 karakter (huruf/angka/underscore).</div>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label"><?= $isEdit ? 'New Password (optional)' : 'Password' ?></label>
      <input id="password" type="password" name="password" class="form-control" autocomplete="new-password">
      <?php if($isEdit): ?><div class="form-text">Kosongkan jika tidak ingin mengubah password.</div><?php endif; ?>
      <div class="invalid-feedback">Password minimal 6 karakter.</div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary"><?= $isEdit ? 'Update' : 'Save' ?></button>
      <a class="btn btn-outline-secondary" href="<?= site_url('admin/students') ?>">Cancel</a>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form   = document.getElementById('studentForm');
  const isEdit = <?= $isEdit ? 'true' : 'false' ?>;

  const idEl   = document.getElementById('student_id'); 
  const yearEl = document.getElementById('entry_year');
  const nameEl = document.getElementById('full_name');
  const userEl = document.getElementById('username');
  const passEl = document.getElementById('password');

  function setErr(el, msg){
    el.classList.add('is-invalid');
    el.setAttribute('aria-invalid','true');
    const fb = el.parentElement.querySelector('.invalid-feedback');
    if (fb) fb.textContent = msg;
  }
  function clearErr(el){
    el.classList.remove('is-invalid');
    el.removeAttribute('aria-invalid');
  }

  function validateNIM(){
    if (!idEl) return true;
    const v = (idEl.value || '').trim();
    if (!/^\d{6,}$/.test(v)) { setErr(idEl, 'NIM wajib angka (min. 6 digit).'); return false; }
    clearErr(idEl); return true;
  }
  function validateYear(){
    const n = Number(yearEl.value);
    if (!Number.isInteger(n) || n < 2000 || n > 2100) { setErr(yearEl, 'Tahun masuk 2000–2100.'); return false; }
    clearErr(yearEl); return true;
  }
  function validateName(){
    const v = (nameEl.value || '').trim();
    if (v.length < 3) { setErr(nameEl, 'Nama lengkap minimal 3 karakter.'); return false; }
    clearErr(nameEl); return true;
  }
  function validateUsername(){
    const v = (userEl.value || '').trim();
    if (!/^[A-Za-z0-9_]{3,20}$/.test(v)) { setErr(userEl, 'Username 3–20 karakter (huruf/angka/underscore).'); return false; }
    clearErr(userEl); return true;
  }
  function validatePassword(){
    const v = passEl.value || '';
    if (!isEdit && v.length < 6) { setErr(passEl, 'Password minimal 6 karakter.'); return false; } 
    if (isEdit && v && v.length < 6) { setErr(passEl, 'Password minimal 6 karakter.'); return false; }
    clearErr(passEl); return true;
  }

  // Live feedback
  idEl?.addEventListener('input', validateNIM);
  yearEl.addEventListener('input', validateYear);
  nameEl.addEventListener('input', validateName);
  userEl.addEventListener('input', validateUsername);
  passEl.addEventListener('input', validatePassword);

  [idEl, yearEl, nameEl, userEl, passEl].forEach(el => {
    el?.addEventListener('blur', () => el && el.classList.remove('is-invalid'));
  });

  // Block submit jika ada yang invalid
  form.addEventListener('submit', (e) => {
    const ok = [
      validateNIM(),
      validateYear(),
      validateName(),
      validateUsername(),
      validatePassword()
    ].every(Boolean);
    if (!ok) e.preventDefault();
  });
});
</script>

<?= $this->endSection() ?>
