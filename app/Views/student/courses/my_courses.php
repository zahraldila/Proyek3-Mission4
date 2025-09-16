<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">My Courses</h3>
  <a class="btn btn-secondary" href="<?= site_url('student/courses') ?>">Browse Courses</a>
</div>

<?php if($s = session()->getFlashdata('success')): ?>
  <div class="alert alert-success"><?= esc($s) ?></div>
<?php elseif($e = session()->getFlashdata('error')): ?>
  <div class="alert alert-danger"><?= esc($e) ?></div>
<?php endif; ?>

<?php if(empty($items)): ?>
  <div class="alert alert-info">Belum ada course yang diambil.</div>
<?php else: ?>
<table class="table table-striped align-middle">
  <thead>
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Credits</th>
      <th>Enrolled At</th>
      <th style="width:140px">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($items as $it): ?>
      <tr>
        <td><?= esc($it['course_id']) ?></td>
        <td><?= esc($it['course_name']) ?></td>
        <td><?= esc($it['credits']) ?></td>
        <td><?= esc($it['enroll_date']) ?></td>
        <td>
          <form method="post"
                action="<?= site_url('student/courses/unenroll/'.$it['course_id']) ?>"
                onsubmit="return confirm('Yakin batal ambil course ini?')">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-outline-danger">Unenroll</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<?= $this->endSection() ?>
