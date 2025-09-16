<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Akademik') ?></title>

  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* ===== THEME ===== */
    :root{
      --brand:#1E3A8A;        /* primary */
      --brand-600:#2563eb;    /* hover */
      --accent:#16a34a;       /* action green */
      --accent-soft:#dcfce7;  /* chip bg */
      --bg:#f6f7fb;           /* app bg */
      --danger:#ef4444;
      --muted:#6b7280;
      --ring:rgba(37,99,235,.18);
    }

    /* ===== LAYOUT ===== */
    body{ background:var(--bg); }
    .app-shell{ min-height:100vh; display:grid; grid-template-columns:260px 1fr; transition:grid-template-columns .25s ease; }
    @media (max-width: 992px){
      .app-shell{ grid-template-columns:1fr; }
      .sidebar{ position:fixed; inset:0 auto 0 -260px; width:260px; transition:all .3s; z-index:1040; }
      .sidebar.show{ left:0; }
      .content{ padding-top:4.5rem; }
    }

    /* ===== SIDEBAR ===== */
    .sidebar{ background:#fff; border-right:1px solid #e5e7eb; padding:18px 14px; transition:width .25s ease, padding .25s ease; }
    .brand{ font-weight:800; letter-spacing:.2px; color:var(--brand); }
    .nav-aside{ gap:6px; display:flex; flex-direction:column; }
    .nav-aside .nav-link{
      display:flex; align-items:center; gap:10px;
      color:#334155; font-weight:500;
      padding:10px 12px; border-radius:12px;
      position:relative; transition:background .15s ease, color .15s ease, padding .25s ease;
      white-space:nowrap;
    }
    .nav-aside .nav-link i{ width:20px; text-align:center; font-size:1.05rem; opacity:.9; }
    .nav-aside .nav-link:hover:not(.active){ background:#f1f5ff; color:#1f2a5a; }
    .nav-aside .nav-link.active{ background:#eef2ff; color:var(--brand); }
    .nav-aside .nav-link.active:hover{ background:#eef2ff; color:var(--brand); }
    .nav-aside .nav-link.active::before{
      content:""; position:absolute; left:-10px; top:12px;
      width:3px; height:20px; border-radius:3px; background:var(--brand);
    }
    .nav-label{
      font-size:.72rem; text-transform:uppercase; letter-spacing:.08em;
      color:#94a3b8; margin:8px 6px 4px;
    }
    .sidebar hr{ border-color:#eef0f4; margin:14px 0; }

    /* ==== Collapsed (desktop) ==== */
    .sidebar.collapsed{ width:80px; padding:18px 8px; }
    .sidebar.collapsed .brand,
    .sidebar.collapsed .nav-label,
    .sidebar.collapsed .user-name{
      display:none !important;
    }
    .sidebar.collapsed .nav-link{ justify-content:center; padding:10px; }
    .sidebar.collapsed .nav-link span{ display:none; }
    .sidebar.collapsed .nav-link.active::before{ left:-4px; }

    /* ===== TOPBAR (mobile) ===== */
    .topbar{ background:#fff; border-bottom:1px solid #e5e7eb; }

    /* ===== BUTTONS ===== */
    .btn-primary{ background:var(--brand); border-color:var(--brand); }
    .btn-primary:hover{ background:var(--brand-600); border-color:var(--brand-600); }
    .btn-outline-danger{ color:var(--danger); border-color:var(--danger); }
    .btn-outline-danger:hover{ background:var(--danger); color:#fff; }

    /* Enroll button */
    .btn-enroll{
      background:var(--accent);
      border:none; color:#fff; font-weight:600;
      border-radius:10px; height:44px;
      transition:all .2s ease-in-out;
    }
    .btn-enroll:hover{ background:#15803d; transform:translateY(-1px); }
    .btn-enroll:focus{ box-shadow:0 0 0 4px var(--ring); }

    /* ===== CARDS & TABLE ===== */
    .card-soft{ border:1px solid #eef0f4; border-radius:16px; box-shadow:0 8px 24px rgba(2,6,23,.06); }
    .card-soft:hover{ box-shadow:0 10px 28px rgba(2,6,23,.08); }
    table.table th{ color:#64748b; font-weight:600; }

    /* ===== STATS ===== */
    .stat{ display:flex; gap:.8rem; align-items:center; }
    .stat .icon{ width:40px; height:40px; border-radius:.8rem; display:grid; place-items:center; background:#eef2ff; color:var(--brand); }

    /* ===== COURSE UI ===== */
    .badge-sks{ background:#eef2ff; color:var(--brand); font-weight:600; border-radius:10px; }
    .course-title{ font-size:18px; font-weight:700; color:#0f172a; }
    .course-meta{ color:#6b7280; font-size:12px; }
    .chip{ display:inline-flex; align-items:center; gap:.4rem; padding:.25rem .6rem; border-radius:999px; font-size:12px; font-weight:600; }
    .chip-ok{ background:var(--accent-soft); color:#166534; }

    /* Hide toggle on mobile (pakai off-canvas tombol list) */
    @media (max-width: 992px){
      .desktop-toggle{ display:none; }
    }
  </style>
</head>
<body>
<div class="app-shell">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">

    <!-- bar atas (mobile close & desktop collapse) -->
    <div class="d-flex align-items-center justify-content-between mb-2">
      <div class="brand">Akademik</div>
      <div class="d-flex align-items-center gap-1">
        <!-- desktop collapse toggle -->
        <button class="btn btn-sm desktop-toggle" id="collapseToggle" title="Collapse sidebar">
          <i class="bi bi-chevron-double-left"></i>
        </button>
        <!-- mobile close -->
        <button class="btn btn-sm d-lg-none" onclick="document.getElementById('sidebar').classList.remove('show')">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
    </div>

    <?php
      $role = session('role');
      $uri  = service('uri');
      $seg1 = $uri->getSegment(2) ?? '';
      $isActive = function (string $expect) use ($seg1) { return $seg1 === $expect ? 'active' : ''; };
    ?>

    <nav class="nav-aside">
      <?php if(session('full_name')): ?>
        <div class="d-flex align-items-center gap-2 p-2 mb-2" style="border:1px solid #eef0f4;border-radius:12px;">
          <div class="rounded-circle d-flex align-items-center justify-content-center"
               style="width:28px;height:28px;background:#eef2ff;color:var(--brand);font-weight:700;">
            <?= strtoupper(substr(trim(session('full_name')),0,1)) ?>
          </div>
          <div class="small text-truncate user-name" style="max-width:140px;"><?= esc(session('full_name')) ?></div>
        </div>
      <?php endif; ?>

      <?php if($role==='admin'): ?>
        <div class="nav-label">Admin</div>
        <a class="nav-link <?= $isActive('') ?>" href="<?= site_url('admin') ?>">
          <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
        <a class="nav-link <?= $isActive('courses') ?>" href="<?= site_url('admin/courses') ?>">
          <i class="bi bi-journal-text"></i> <span>Manage Courses</span>
        </a>
        <a class="nav-link <?= $isActive('students') ?>" href="<?= site_url('admin/students') ?>">
          <i class="bi bi-people"></i> <span>Manage Students</span>
        </a>

      <?php elseif($role==='student'): ?>
        <div class="nav-label">Student</div>
        <a class="nav-link <?= $isActive('') ?>" href="<?= site_url('student') ?>">
          <i class="bi bi-house"></i> <span>Dashboard</span>
        </a>
        <a class="nav-link <?= $isActive('courses') ?>" href="<?= site_url('student/courses') ?>">
          <i class="bi bi-grid"></i> <span>Courses</span>
        </a>
        <a class="nav-link <?= $isActive('my-courses') ?>" href="<?= site_url('student/my-courses') ?>">
          <i class="bi bi-bookmark-check"></i> <span>My Courses</span>
        </a>
      <?php else: ?>
        <a class="nav-link" href="<?= site_url('/') ?>"><i class="bi bi-house"></i> <span>Home</span></a>
      <?php endif; ?>

      <hr>

      <?php if(session('logged_in')): ?>
        <a class="nav-link" href="<?= site_url('logout') ?>">
          <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
        </a>
      <?php else: ?>
        <a class="nav-link" href="<?= site_url('login') ?>">
          <i class="bi bi-person-circle"></i> <span>Login</span>
        </a>
      <?php endif; ?>
    </nav>
  </aside>

  <!-- ===== CONTENT ===== -->
  <main class="content">
    <!-- topbar mobile -->
    <div class="topbar position-fixed top-0 start-0 end-0 d-lg-none">
      <div class="container-fluid d-flex align-items-center gap-2 py-2">
        <button class="btn" onclick="document.getElementById('sidebar').classList.add('show')">
          <i class="bi bi-list"></i>
        </button>
        <div class="fw-semibold">Menu</div>
      </div>
    </div>

    <div class="container py-4">
      <?= $this->renderSection('content') ?>
    </div>
  </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Desktop collapse toggle
  (function(){
    const sidebar = document.getElementById('sidebar');
    const appShell = document.querySelector('.app-shell');
    const btn = document.getElementById('collapseToggle');

    if(!btn) return;
    btn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      // optional: kecilkan grid col saat collapse
      if (sidebar.classList.contains('collapsed')) {
        appShell.style.gridTemplateColumns = '80px 1fr';
        btn.innerHTML = '<i class="bi bi-chevron-double-right"></i>';
        btn.title = 'Expand sidebar';
      } else {
        appShell.style.gridTemplateColumns = '260px 1fr';
        btn.innerHTML = '<i class="bi bi-chevron-double-left"></i>';
        btn.title = 'Collapse sidebar';
      }
    });
  })();
</script>
</body>
</html>
