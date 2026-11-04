<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/frontend-icons.php';
require_once __DIR__ . '/includes/boat-booking-helpers.php';
require_once __DIR__ . '/includes/boat-booking-layout.php';
tt_require_admin();

$iconOptions = tt_bb_icon_options();

$data = tt_read_data();
$bb = tt_bb_get($data);
$addons = $bb['addons'] ?? [];
$addonId = trim($_GET['id'] ?? $_POST['original_id'] ?? '');
$idx = $addonId !== '' ? tt_bb_find_addon_index($addons, $addonId) : -1;
$addon = $idx >= 0 ? $addons[$idx] : null;
$isNew = $addon === null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = tt_read_data();
    $bb = tt_bb_get($data);
    $addons = $bb['addons'] ?? [];
    $originalId = trim($_POST['original_id'] ?? '');
    $idx = $originalId !== '' ? tt_bb_find_addon_index($addons, $originalId) : -1;

    $row = [
        'id' => trim($_POST['id'] ?? ''),
        'icon' => trim($_POST['icon'] ?? 'plus'),
        'label' => trim($_POST['label'] ?? ''),
        'desc' => trim($_POST['desc'] ?? ''),
        'price' => $_POST['price'] ?? 0,
        'unit' => $_POST['unit'] ?? 'flat',
        'priceLabel' => trim($_POST['priceLabel'] ?? ''),
        'qtyLabel' => trim($_POST['qtyLabel'] ?? ''),
    ];
    if ($row['id'] === '' && $row['label'] !== '') {
        $row['id'] = 'menu-' . tt_slugify($row['label']);
    }

    $item = tt_bb_parse_addon_post($row);
    if ($item === null) {
        tt_set_flash('Please enter an ID and a display name', 'error');
        $redirectId = $originalId !== '' ? '?id=' . urlencode($originalId) : '';
        header('Location: boat-booking-addon-edit.php' . $redirectId);
        exit;
    }

    $newId = (string) $item['id'];
    if ($idx >= 0) {
        if ($newId !== $originalId) {
            $addons = array_values(array_filter($addons, static fn($a) => ($a['id'] ?? '') !== $originalId));
            if (tt_bb_find_addon_index($addons, $newId) >= 0) {
                tt_set_flash('This ID already exists — use a different name', 'error');
                header('Location: boat-booking-addon-edit.php?id=' . urlencode($originalId));
                exit;
            }
            $addons[] = $item;
        } else {
            $addons[$idx] = $item;
        }
    } else {
        if (tt_bb_find_addon_index($addons, $newId) >= 0) {
            tt_set_flash('This ID already exists', 'error');
            header('Location: boat-booking-addon-edit.php');
            exit;
        }
        $addons[] = $item;
    }

    $bb['addons'] = array_values($addons);
    tt_bb_save($bb);
    tt_set_flash('Add-on saved');
    header('Location: boat-booking-addon-edit.php?id=' . urlencode($newId));
    exit;
}

$addon = $addon ?? [
    'id' => '',
    'icon' => 'plus',
    'label' => '',
    'desc' => '',
    'price' => 0,
    'unit' => 'flat',
    'priceLabel' => '',
];

$pageTitle = $isNew ? 'Add add-on' : 'Edit add-on';
tt_bb_page_start($pageTitle, 'addons');
?>

<div class="card admin-preview-bar adm-bb-profile-bar">
  <a class="btn btn-ghost btn-sm" href="boat-booking-addons.php">← Add-on list</a>
  <a class="btn btn-ghost btn-sm" href="../boat-book.html" target="_blank" rel="noopener">Test booking page ↗</a>
</div>

<form method="post">
  <input type="hidden" name="original_id" value="<?= htmlspecialchars($isNew ? '' : (string) ($addon['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/>

  <div class="card">
    <h2>Add-on details</h2>
    <div class="grid-2">
      <div class="field">
        <label>Display name</label>
        <input name="label" required value="<?= htmlspecialchars((string) ($addon['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Lunch"/>
      </div>
      <div class="field">
        <label>ID</label>
        <input name="id" value="<?= htmlspecialchars((string) ($addon['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="menu-food"/>
        <p class="field-hint">Leave blank to auto-generate from the name, e.g. menu-food</p>
      </div>
      <div class="field">
        <label>Icon</label>
        <?php tt_icon_select('icon', $iconOptions, (string) ($addon['icon'] ?? 'plus')); ?>
      </div>
      <div class="field">
        <label>Price (THB)</label>
        <input name="price" type="number" min="0" value="<?= (int) ($addon['price'] ?? 0) ?>"/>
      </div>
      <div class="field">
        <label>Unit</label>
        <?php tt_bb_render_addon_unit_select('unit', (string) ($addon['unit'] ?? 'flat')); ?>
      </div>
      <div class="field">
        <label>Price text</label>
        <input name="priceLabel" value="<?= htmlspecialchars((string) ($addon['priceLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="100 THB/person"/>
        <p class="field-hint">Shown on the booking card — can be left blank, the numeric price will be used</p>
      </div>
      <div class="field">
        <label>Quantity label (perQty/perVan)</label>
        <input name="qtyLabel" value="<?= htmlspecialchars((string) ($addon['qtyLabel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="person / set"/>
      </div>
      <div class="field" style="grid-column:1/-1">
        <label>Description</label>
        <input name="desc" value="<?= htmlspecialchars((string) ($addon['desc'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="1 lunch set per person"/>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Save add-on</button>
    <a class="btn btn-ghost" href="boat-booking-addons.php">Back</a>
  </div>
</form>

<?php tt_admin_footer(); ?>
