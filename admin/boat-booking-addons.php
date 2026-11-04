<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/boat-booking-helpers.php';
require_once __DIR__ . '/includes/boat-booking-layout.php';
tt_require_admin();

$data = tt_read_data();
$bb = tt_bb_get($data);
$addons = $bb['addons'] ?? [];

if (isset($_GET['delete']) && $_GET['delete'] !== '') {
    $deleteId = (string) $_GET['delete'];
    $addons = array_values(array_filter($addons, static fn($a) => ($a['id'] ?? '') !== $deleteId));
    $bb['addons'] = $addons;
    tt_bb_save($bb);
    tt_set_flash('Add-on deleted');
    header('Location: boat-booking-addons.php');
    exit;
}

tt_bb_page_start('⑥ Add-ons', 'addons');
?>

<div class="form-actions" style="border:0;margin-bottom:16px;padding:0">
  <a class="btn btn-primary" href="boat-booking-addon-edit.php">+ Add add-on</a>
</div>

<div class="card">
  <p class="field-hint" style="margin:0 0 16px">Default for all boats — each boat type profile can define its own add-ons</p>
  <?php if (!$addons): ?>
    <p class="field-hint">No add-ons yet — click <strong>+ Add add-on</strong> to create the first one</p>
  <?php else: ?>
  <table class="table">
    <thead>
      <tr><th>Name</th><th>Unit</th><th>Price</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($addons as $a):
        $id = (string) ($a['id'] ?? '');
        $unit = (string) ($a['unit'] ?? 'flat');
        $price = (int) ($a['price'] ?? 0);
        $priceLabel = trim((string) ($a['priceLabel'] ?? ''));
      ?>
      <tr>
        <td>
          <strong><?= htmlspecialchars((string) ($a['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
          <?php if (trim((string) ($a['desc'] ?? '')) !== ''): ?>
            <br><small class="field-hint"><?= htmlspecialchars((string) $a['desc'], ENT_QUOTES, 'UTF-8') ?></small>
          <?php endif; ?>
          <br><small class="field-hint"><?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?></small>
        </td>
        <td><?= htmlspecialchars(tt_bb_addon_unit_label($unit), ENT_QUOTES, 'UTF-8') ?></td>
        <td>
          ฿<?= number_format($price) ?>
          <?php if ($priceLabel !== ''): ?>
            <br><small class="field-hint"><?= htmlspecialchars($priceLabel, ENT_QUOTES, 'UTF-8') ?></small>
          <?php endif; ?>
        </td>
        <td class="table-actions">
          <div class="table-actions-row">
            <a class="btn btn-ghost btn-sm" href="boat-booking-addon-edit.php?id=<?= urlencode($id) ?>">Edit</a>
            <a class="btn btn-danger btn-sm" href="boat-booking-addons.php?delete=<?= urlencode($id) ?>" onclick="return confirm('Delete this add-on?')">Delete</a>
            <a class="btn btn-ghost btn-sm" href="../boat-book.html" target="_blank" rel="noopener">View</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php tt_admin_footer(); ?>
