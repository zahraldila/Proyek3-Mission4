<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
  // --- DEFAULTS BIAR GA ERROR DI CREATE ---
  $student = $student ?? [];   // kalau create, kosongkan
  $user    = $user ?? [];      // kalau create, kosongkan

  $isEdit = isset($student['student_id']); // edit jika ada NIM
  $action = $isEdit
    ? site_url('admin/students/edit/'.$student['student_id'])
    : site_url('admin/students/create');

  $errs = $errors ?? session()->getFlashdata('errors') ?? [];
  if (!is_array($errs)) $errs = [];

  // helper value lama / default
  $val = function(string $key, $fallback='') use ($isEdit, $student, $user) {
    if ($isEdit) {
      if ($key === 'username' || $key === 'full_name') {
        return old($key, $user[$key] ?? '');
      }
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
  <form method="post" action="<?= $action ?>">
    <?= csrf_field() ?>

    <?php if(!$isEdit): ?>
      <div class="mb-3">
        <label class="form-label">NIM</label>
        <input name="student_id" class="form-control" maxlength="20"
               value="<?= esc($val('student_id')) ?>" required placeholder="mis. 221234567">
      </div>
    <?php else: ?>
      <div class="mb-3">
        <label class="form-label">NIM</label>
        <input class="form-control" value="<?= esc($student['student_id']) ?>" disabled>
      </div>
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label">Entry Year</label>
      <input type="number" name="entry_year" min="2000" max="2100" class="form-control"
             value="<?= esc($val('entry_year', date('Y'))) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input name="full_name" class="form-control"
             value="<?= esc($val('full_name')) ?>" required placeholder="Nama lengkap">
    </div>

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input name="username" class="form-control"
             value="<?= esc($val('username')) ?>" required placeholder="username login">
    </div>

    <div class="mb-3">
      <label class="form-label"><?= $isEdit ? 'New Password (optional)' : 'Password' ?></label>
      <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?>>
      <?php if($isEdit): ?><div class="form-text">Kosongkan jika tidak ingin mengubah password.</div><?php endif; ?>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary"><?= $isEdit ? 'Update' : 'Save' ?></button>
      <a class="btn btn-outline-secondary" href="<?= site_url('admin/students') ?>">Cancel</a>
    </div>
  </form>
</div>

<?= $this->endSection() ?>
