<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/frontend-icons.php';
tt_require_admin();

$iconOptions = ['cutlery', 'users', 'camera', 'scuba', 'carPick', 'chatBubble', 'anchor', 'car', 'bed', 'ticket', 'briefcase', 'compass'];

$data = tt_read_data();
$options = $data['options'] ?? [];
$id = trim($_GET['id'] ?? '');
$option = null;
$idx = -1;

foreach ($options as $i => $o) {
    if (($o['id'] ?? '') === $id) {
        $option = $o;
        $idx = $i;
        break;
    }
}

$isNew = $id === '' || $option === null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newId = trim($_POST['id'] ?? '');
    if ($newId === '') {
        $newId = tt_slugify($_POST['label'] ?? 'option');
    }

    $unit = trim($_POST['unit'] ?? 'flat');
    $price = (int)($_POST['price'] ?? 0);
    $priceLabel = trim($_POST['priceLabel'] ?? '');
    if ($priceLabel === '') {
        $suffix = $unit === 'per_person' ? '/person' : '/trip';
        $priceLabel = '+' . number_format($price) . ' THB' . $suffix;
    }

    $item = [
        'id' => $newId,
        'icon' => trim($_POST['icon'] ?? 'cutlery'),
        'label' => trim($_POST['label'] ?? ''),
        'desc' => trim($_POST['desc'] ?? ''),
        'price' => $price,
        'unit' => $unit,
        'priceLabel' => $priceLabel,
    ];

    if ($idx >= 0) {
        if ($newId !== $id) {
            foreach ($options as $j => $o) {
                if ($j !== $idx && ($o['id'] ?? '') === $newId) {
                    tt_set_flash('This ID already exists — use a different ID');
                    header('Location: option-edit.php?id=' . urlencode($id));
                    exit;
                }
            }
        }
        $options[$idx] = $item;
    } else {
        foreach ($options as $o) {
            if (($o['id'] ?? '') === $newId) {
                tt_set_flash('This ID already exists — use a different ID');
                header('Location: option-edit.php');
                exit;
            }
        }
        $options[] = $item;
    }

    $data['options'] = $options;
    tt_write_data($data);
    tt_set_flash('Booking option saved');
    header('Location: option-edit.php?id=' . urlencode($newId));
    exit;
}

$pageTitle = $isNew ? 'Add booking option' : 'Edit booking option';
tt_admin_header($pageTitle, 'options');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post">
  <div class="card">
    <h2>Option details</h2>
    <div class="grid-2">
      <div class="field"><label>Display name</label><input name="label" required value="<?= htmlspecialchars($option['label'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Lunch"/></div>
      <div class="field"><label>ID (slug)</label><input name="id" value="<?= htmlspecialchars($option['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="lunch, guide, photo"/></div>
      <div class="field"><label>Icon</label>
        <?php tt_icon_select('icon', $iconOptions, $option['icon'] ?? 'cutlery'); ?>
      </div>
      <div class="field"><label>Price (THB)</label><input name="price" type="number" min="0" value="<?= (int)($option['price'] ?? 0) ?>"/></div>
      <div class="field"><label>Pricing unit</label>
        <select name="unit">
          <option value="flat"<?= ($option['unit'] ?? 'flat') === 'flat' ? ' selected' : '' ?>>Per trip (flat)</option>
          <option value="per_person"<?= ($option['unit'] ?? '') === 'per_person' ? ' selected' : '' ?>>Per person (per_person)</option>
        </select>
      </div>
      <div class="field"><label>Price text</label><input name="priceLabel" value="<?= htmlspecialchars($option['priceLabel'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="+250 THB/person (blank = auto-generate)"/></div>
      <div class="field" style="grid-column:1/-1"><label>Short description</label><input name="desc" value="<?= htmlspecialchars($option['desc'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Fresh seafood set"/></div>
    </div>
    <p class="field-hint">This option appears on the <strong>booking.html</strong> page (legacy booking) — for the <strong>boat-book.html</strong> page, edit it in the <a href="boat-booking-profiles.php">Charter boat booking</a> menu</p>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn btn-ghost" href="options.php">Back</a>
      <a class="btn btn-ghost" href="../booking.html" target="_blank">View booking page</a>
    </div>
  </div>
</form>

<?php tt_admin_footer(); ?>
