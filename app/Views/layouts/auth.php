<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Login') ?> · Akademik</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --brand:#1E3A8A; --brand-600:#2563eb;
      --bg:#f6f7fb; --ring:rgba(37,99,235,.18);
    }
    body{ background:var(--bg); }
    .auth-wrap{ min-height:100vh; display:grid; place-items:center; padding:24px; }
    .auth-card{
      width:100%; max-width:520px; background:#fff; border-radius:18px;
      border:1px solid #eef0f4; box-shadow:0 14px 40px rgba(2,6,23,.08);
      padding:28px; 
    }
    .brand-dot{ width:36px;height:36px; border-radius:10px;
      background:#eef2ff;color:var(--brand); display:flex; align-items:center; justify-content:center; font-weight:800;
    }
    .btn-primary{ background:var(--brand-600); border-color:var(--brand-600); height:44px; font-weight:600; }
    .btn-primary:hover{ filter:brightness(.95); }
    .form-control{
      background:#edf2ff3d; border-color:#e5e7eb;
    }
    .form-control:focus{
      border-color:var(--brand-600); box-shadow:0 0 0 .25rem var(--ring);
    }
  </style>
</head>
<body>
  <div class="auth-wrap">
    <?= $this->renderSection('content') ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
