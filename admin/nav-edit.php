<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$items = $data['navItems'] ?? [];
$idx = isset($_GET['i']) ? (int)$_GET['i'] : -1;
$item = ($idx >= 0 && isset($items[$idx])) ? $items[$idx] : null;
$isNew = $item === null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postIdx = isset($_POST['idx']) ? (int)$_POST['idx'] : -1;
    $label = trim($_POST['label'] ?? '');
    $href = trim($_POST['href'] ?? '');

    if ($label === '') {
        tt_set_flash('Please enter a menu name');
        header('Location: nav-edit.php' . ($postIdx >= 0 ? '?i=' . $postIdx : ''));
        exit;
    }

    $entry = ['label' => $label, 'href' => $href];

    if ($postIdx >= 0 && isset($items[$postIdx])) {
        $items[$postIdx] = $entry;
    } else {
        $items[] = $entry;
        $postIdx = count($items) - 1;
    }

    $data['navItems'] = $items;
    tt_write_data($data);
    tt_set_flash('Menu item saved');
    header('Location: nav-edit.php?i=' . $postIdx);
    exit;
}

$pageTitle = $isNew ? 'Add navigation menu item' : 'Edit navigation menu item';
tt_admin_header($pageTitle, 'nav');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post">
  <div class="card">
    <h2>Menu item</h2>
    <p class="field-hint">The order on the website follows the order in the list page — use a link within the website, e.g. <code>boats.html</code>, or an external URL</p>

    <input type="hidden" name="idx" value="<?= $isNew ? -1 : $idx ?>"/>

    <div class="grid-2">
      <div class="field"><label>Menu name</label><input name="label" required value="<?= htmlspecialchars($item['label'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Boat types"/></div>
      <div class="field"><label>Link</label><input name="href" value="<?= htmlspecialchars($item['href'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="boats.html"/></div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn btn-ghost" href="nav.php">Back</a>
      <?php if (!$isNew && !empty($item['href'])): ?>
        <a class="btn btn-ghost" href="../<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">View page</a>
      <?php endif; ?>
      <?php if (!$isNew): ?>
        <a class="btn btn-danger" href="nav.php?delete=<?= $idx ?>" onclick="return confirm('Delete this menu item?')">Delete this menu item</a>
      <?php endif; ?>
    </div>
  </div>
</form>

<?php tt_admin_footer(); ?>
