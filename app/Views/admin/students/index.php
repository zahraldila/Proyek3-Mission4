<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Students</h3>
  <a class="btn btn-success" href="<?= site_url('admin/students/create') ?>">
    <i class="bi bi-plus-lg me-1"></i>Add Student
  </a>
</div>

<?= $this->include('partials/flash') ?>

<div class="card card-soft p-3">
  <?php if (empty($students)): ?>
    <div class="text-center text-secondary py-5">
      <i class="bi bi-people" style="font-size:2rem"></i>
      <p class="mt-2 mb-0">Belum ada student. Klik <b>Add Student</b> untuk menambah.</p>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width:140px">NIM</th>
            <th>Name</th>
            <th>Username</th>
            <th style="width:130px">Entry Year</th>
            <th style="width:200px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($students as $st): ?>
            <tr>
              <td class="fw-semibold"><?= esc($st['student_id']) ?></td>
              <td><?= esc($st['full_name']) ?></td>
              <td><span class="badge text-bg-secondary"><?= esc($st['username']) ?></span></td>
              <td><?= esc($st['entry_year']) ?></td>
              <td class="d-flex gap-2">
                <a class="btn btn-sm btn-primary"
                   href="<?= site_url('admin/students/edit/'.$st['student_id']) ?>">
                  <i class="bi bi-pencil-square me-1"></i>Edit
                </a>

                <button type="button"
                  class="btn btn-sm btn-outline-danger"
                  data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                  data-action="<?= site_url('admin/students/delete/'.$st['student_id']) ?>"
                  data-name="<?= esc($st['full_name']) ?>"
                  data-meta-label="NIM"
                  data-meta="<?= esc($st['student_id']) ?>">
                  <i class="bi bi-trash3 me-1"></i>Delete
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
