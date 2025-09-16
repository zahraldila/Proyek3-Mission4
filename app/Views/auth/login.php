<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-card">
  <div class="d-flex align-items-center gap-2 mb-2">
    <div class="brand-dot">A</div>
    <div class="fw-bold" style="color:#1E3A8A">Akademik</div>
  </div>

  <h3 class="mb-1">Login</h3>
  <div class="text-secondary mb-3">Masuk untuk melanjutkan.</div>

  <?php if($e = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc($e) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('login') ?>" class="vstack gap-3">
    <?= csrf_field() ?>

    <div>
      <label class="form-label">Username</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-person"></i></span>
        <input name="username" class="form-control" value="<?= old('username') ?>" placeholder="username" required>
      </div>
    </div>

    <div>
      <label class="form-label">Password</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock"></i></span>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
    </div>

    <button class="btn btn-primary w-100">Masuk</button>
  </form>
</div>

<?= $this->endSection() ?>
