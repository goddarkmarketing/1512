<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/image-field.php';
tt_require_admin();

$data = tt_read_data();
$slides = $data['heroSlides'] ?? [];

if (isset($_GET['delete']) && $_GET['delete'] !== '') {
    $idx = (int)$_GET['delete'];
    if (isset($slides[$idx])) {
        array_splice($slides, $idx, 1);
        $data['heroSlides'] = array_values($slides);
        tt_write_data($data);
        tt_set_flash('Slide deleted');
    }
    header('Location: hero.php');
    exit;
}

tt_admin_header('Home banner', 'hero');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="form-actions" style="border:0;margin-bottom:16px;padding:0">
  <a class="btn btn-primary" href="hero-edit.php">+ Add slide</a>
  <a class="btn btn-ghost" href="media.php">Upload image</a>
</div>

<p class="field-hint" style="margin:0 0 16px"><?= htmlspecialchars(tt_image_field_label('Home banner slide', null, 'hero_slide'), ENT_QUOTES, 'UTF-8') ?> · <a href="media.php">View all size tables</a></p>

<div class="card">
  <?php if (!$slides): ?>
    <p class="field-hint">No slides yet — click the button above to add the first one</p>
  <?php else: ?>
  <table class="table">
    <thead>
      <tr><th>Order</th><th>Image</th><th>Description (alt)</th><th>File path</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($slides as $i => $s): ?>
        <tr>
          <td>#<?= $i + 1 ?></td>
          <td>
            <?php if (!empty($s['src'])): ?>
              <img src="../<?= htmlspecialchars($s['src'], ENT_QUOTES, 'UTF-8') ?>" alt=""/>
            <?php else: ?>
              <span class="field-hint">—</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($s['alt'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td><small class="field-hint"><?= htmlspecialchars($s['src'] ?? '—', ENT_QUOTES, 'UTF-8') ?></small></td>
          <td class="table-actions">
            <div class="table-actions-row">
              <a class="btn btn-ghost btn-sm" href="hero-edit.php?i=<?= $i ?>">Edit</a>
              <a class="btn btn-danger btn-sm" href="hero.php?delete=<?= $i ?>" onclick="return confirm('Delete this slide?')">Delete</a>
              <a class="btn btn-ghost btn-sm" href="../index.html" target="_blank">View</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php tt_admin_footer(); ?>
