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
  <form method="post" action="<?= $action ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label class="form-label">Course Name</label>
      <input type="text" name="course_name" class="form-control"
             value="<?= esc($val('course_name')) ?>" placeholder="mis. Grafika Komputer" autofocus>
    </div>
    <div class="mb-3">
      <label class="form-label">Credits (1–6)</label>
      <input type="number" name="credits" min="1" max="6" class="form-control"
             value="<?= esc($val('credits',3)) ?>">
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-primary"><?= $isEdit ? 'Update' : 'Save' ?></button>
      <a class="btn btn-outline-secondary" href="<?= site_url('admin/courses') ?>">Cancel</a>
    </div>
  </form>
</div>

<?= $this->endSection() ?>
