<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Courses</h3>
  <a class="btn btn-success" href="<?= site_url('admin/courses/create') ?>">
    <i class="bi bi-plus-lg me-1"></i>Add Course
  </a>
</div>

<?= $this->include('partials/flash') ?>

<div class="card card-soft p-3">
  <?php if (empty($courses)): ?>
    <div class="text-center text-secondary py-5">
      <i class="bi bi-journal-text" style="font-size:2rem"></i>
      <p class="mt-2 mb-0">Belum ada course. Klik <b>Add Course</b> untuk menambah.</p>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width:80px">#</th>
            <th>Name</th>
            <th style="width:120px">Credits</th>
            <th style="width:180px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($courses as $c): ?>
            <tr>
              <td><?= esc($c['course_id']) ?></td>
              <td class="fw-semibold"><?= esc($c['course_name']) ?></td>
              <td><span class="badge text-bg-secondary"><?= esc($c['credits']) ?> SKS</span></td>
              <td class="d-flex gap-2">
                <a class="btn btn-sm btn-primary"
                   href="<?= site_url('admin/courses/edit/'.$c['course_id']) ?>">
                  <i class="bi bi-pencil-square me-1"></i>Edit
                </a>

                <button type="button"
                  class="btn btn-outline-danger btn-sm"
                  data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                  data-action="<?= site_url('admin/courses/delete/'.$c['course_id']) ?>"
                  data-name="<?= esc($c['course_name']) ?>"
                  data-meta-label="SKS"
                  data-meta="<?= esc($c['credits']) ?>">
                  <i class="bi bi-trash"></i> Delete
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
