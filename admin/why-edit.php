<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/frontend-icons.php';
tt_require_admin();

$iconOptions = ['shield', 'star', 'compass', 'chat', 'anchor', 'users'];

$data = tt_read_data();
$items = $data['whyUs'] ?? [];
$idx = isset($_GET['i']) ? (int)$_GET['i'] : -1;
$item = ($idx >= 0 && isset($items[$idx])) ? $items[$idx] : null;
$isNew = $item === null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postIdx = isset($_POST['idx']) ? (int)$_POST['idx'] : -1;
    $title = trim($_POST['title'] ?? '');
    if ($title === '') {
        tt_set_flash('Please enter a title');
        header('Location: why-edit.php' . ($postIdx >= 0 ? '?i=' . $postIdx : ''));
        exit;
    }

    $entry = [
        'icon' => trim($_POST['icon'] ?? 'star'),
        'title' => $title,
        'desc' => trim($_POST['desc'] ?? ''),
    ];

    if ($postIdx >= 0 && isset($items[$postIdx])) {
        $items[$postIdx] = $entry;
    } else {
        $items[] = $entry;
        $postIdx = count($items) - 1;
    }

    $data['whyUs'] = $items;
    tt_write_data($data);
    tt_set_flash('Highlight saved');
    header('Location: why-edit.php?i=' . $postIdx);
    exit;
}

$pageTitle = $isNew ? 'Add highlight' : 'Edit highlight';
tt_admin_header($pageTitle, 'why');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post">
  <div class="card">
    <h2>Service highlight</h2>
    <input type="hidden" name="idx" value="<?= $isNew ? -1 : $idx ?>"/>

    <div class="grid-2">
      <div class="field"><label>Title</label><input name="title" required value="<?= htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/></div>
      <div class="field"><label>Icon</label>
        <?php tt_icon_select('icon', $iconOptions, $item['icon'] ?? 'star'); ?>
      </div>
      <div class="field" style="grid-column:1/-1"><label>Description</label><textarea name="desc"><?= htmlspecialchars($item['desc'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
    </div>

    <p class="field-hint">Shown on the home page and the About us page</p>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn btn-ghost" href="why.php">Back</a>
      <a class="btn btn-ghost" href="../about.html" target="_blank">View page</a>
      <?php if (!$isNew): ?>
        <a class="btn btn-danger" href="why.php?delete=<?= $idx ?>" onclick="return confirm('Delete this highlight?')">Delete this entry</a>
      <?php endif; ?>
    </div>
  </div>
</form>

<?php tt_admin_footer(); ?>
