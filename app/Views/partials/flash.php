<?php if($m = session()->getFlashdata('success')): ?>
  <div class="alert alert-success"><?= esc($m) ?></div>
<?php elseif($m = session()->getFlashdata('error')): ?>
  <div class="alert alert-danger"><?= esc($m) ?></div>
<?php endif; ?>
