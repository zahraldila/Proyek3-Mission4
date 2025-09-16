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
              <td>
                <a class="btn btn-sm btn-primary"
                   href="<?= site_url('admin/courses/edit/'.$c['course_id']) ?>">
                  <i class="bi bi-pencil-square me-1"></i>Edit
                </a>
                <form class="d-inline" method="post"
                      action="<?= site_url('admin/courses/delete/'.$c['course_id']) ?>"
                      onsubmit="return confirm('Hapus course ini?')">
                  <?= csrf_field() ?>
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash3 me-1"></i>Delete
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
