<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
  <div class="text-center">
    <h1>Selamat Datang di Website Akademik</h1>
    <p class="lead">Sistem Akademik sederhana dengan login Admin & Student</p>

    <?php if (session('logged_in')): ?>
      <a href="<?= site_url(session('role')) ?>" class="btn btn-success">Ke Dashboard</a>
    <?php endif; ?>
  </div>
<?= $this->endSection() ?>
