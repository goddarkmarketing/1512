<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$items = $data['navItems'] ?? [];

if (isset($_GET['delete']) && $_GET['delete'] !== '') {
    $idx = (int)$_GET['delete'];
    if (isset($items[$idx])) {
        array_splice($items, $idx, 1);
        $data['navItems'] = array_values($items);
        tt_write_data($data);
        tt_set_flash('Menu item deleted');
    }
    header('Location: nav.php');
    exit;
}

tt_admin_header('Navigation menu', 'nav');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="form-actions" style="border:0;margin-bottom:16px;padding:0">
  <a class="btn btn-primary" href="nav-edit.php">+ Add menu item</a>
</div>

<div class="card">
  <?php if (!$items): ?>
    <p class="field-hint">No menu items yet — click the button above to add the first one</p>
  <?php else: ?>
  <p class="field-hint" style="margin-top:0;margin-bottom:16px">Order from top to bottom = left to right on the website</p>
  <table class="table">
    <thead>
      <tr><th>Order</th><th>Menu name</th><th>Link</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($items as $i => $item): ?>
        <tr>
          <td>#<?= $i + 1 ?></td>
          <td><strong><?= htmlspecialchars($item['label'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong></td>
          <td><small class="field-hint"><?= htmlspecialchars($item['href'] ?? '—', ENT_QUOTES, 'UTF-8') ?></small></td>
          <td class="table-actions">
            <div class="table-actions-row">
              <a class="btn btn-ghost btn-sm" href="nav-edit.php?i=<?= $i ?>">Edit</a>
              <a class="btn btn-danger btn-sm" href="nav.php?delete=<?= $i ?>" onclick="return confirm('Delete this menu item?')">Delete</a>
              <?php if (!empty($item['href'])): ?>
                <a class="btn btn-ghost btn-sm" href="../<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">View</a>
              <?php else: ?>
                <span class="btn btn-ghost btn-sm" style="opacity:.4;pointer-events:none">View</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php tt_admin_footer(); ?>
