<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$updated = $data['meta']['updated'] ?? '—';

tt_admin_header('Overview', 'dashboard');
$flash = tt_flash();
if ($flash): ?>
  <div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="stats">
  <div class="stat"><div class="stat-num"><?= count($data['boats'] ?? []) ?></div><div class="stat-label">Boat types</div></div>
  <div class="stat"><div class="stat-num"><?= count($data['programs'] ?? []) ?></div><div class="stat-label">Programs</div></div>
  <div class="stat"><div class="stat-num"><?= count($data['articles'] ?? []) ?></div><div class="stat-label">Articles</div></div>
  <div class="stat"><div class="stat-num"><?= count($data['reviews'] ?? []) ?></div><div class="stat-label">Reviews</div></div>
</div>

<div class="card">
  <h2>Latest data</h2>
  <p>Last updated: <strong><?= htmlspecialchars($updated, ENT_QUOTES, 'UTF-8') ?></strong></p>
  <p class="field-hint">Saving from the admin updates <code>data/site.json</code> and <code>assets/js/data-fallback.js</code> immediately</p>
  <div class="form-actions" style="border:0;padding-top:8px">
    <a class="btn btn-primary" href="../index.html" target="_blank">Open website</a>
    <a class="btn btn-ghost" href="section-headings.php">Section headings</a>
    <a class="btn btn-ghost" href="site.php">Edit site info</a>
    <a class="btn btn-ghost" href="articles.php">Manage articles</a>
    <a class="btn btn-ghost" href="backup.php">Backup & restore</a>
  </div>
</div>

<div class="card">
  <h2>Edit by section</h2>
  <p class="field-hint" style="margin-bottom:16px">Choose a menu on the left to edit each section of the website</p>
  <div class="grid-2">
    <?php foreach (tt_admin_nav() as $item): if ($item['id'] === 'dashboard') continue; ?>
      <a class="btn btn-ghost" href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" style="justify-content:flex-start">
        <span class="admin-nav-icon"><?= tt_admin_icon($item['id']) ?></span>
        <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<?php tt_admin_footer(); ?>
