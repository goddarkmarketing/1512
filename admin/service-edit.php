<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$iconOptions = ['bigBoat', 'longtail', 'speedboat', 'bed', 'ticket', 'car', 'briefcase', 'scuba', 'cutlery', 'edit', 'camera', 'anchor', 'route', 'calculator', 'line', 'compass', 'users', 'info'];

$data = tt_read_data();
$services = $data['services'] ?? [];
$key = trim($_GET['key'] ?? '');
$service = null;
$idx = -1;

foreach ($services as $i => $s) {
    $itemKey = $s['id'] ?? (string)$i;
    if ($itemKey === $key || (string)$i === $key) {
        $service = $s;
        $idx = $i;
        break;
    }
}

$isNew = $key === '' || $service === null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postIdx = isset($_POST['idx']) ? (int)$_POST['idx'] : -1;
    $newId = trim($_POST['id'] ?? '');
    if ($newId === '') {
        $newId = tt_slugify($_POST['title'] ?? 'service');
    }

    $item = [
        'id' => $newId,
        'icon' => trim($_POST['icon'] ?? 'anchor'),
        'title' => trim($_POST['title'] ?? ''),
        'href' => trim($_POST['href'] ?? ''),
    ];
    if (!empty($_POST['external'])) {
        $item['external'] = true;
    }

    if ($postIdx >= 0 && isset($services[$postIdx])) {
        if ($newId !== ($services[$postIdx]['id'] ?? '')) {
            foreach ($services as $j => $s) {
                if ($j !== $postIdx && ($s['id'] ?? '') === $newId) {
                    tt_set_flash('This ID already exists — use a different ID');
                    header('Location: service-edit.php?key=' . urlencode($key));
                    exit;
                }
            }
        }
        $services[$postIdx] = $item;
    } else {
        foreach ($services as $s) {
            if (($s['id'] ?? '') === $newId) {
                tt_set_flash('This ID already exists — use a different ID');
                header('Location: service-edit.php');
                exit;
            }
        }
        $services[] = $item;
        $postIdx = count($services) - 1;
    }

    $data['services'] = $services;
    tt_write_data($data);
    tt_set_flash('Service saved');
    header('Location: service-edit.php?key=' . urlencode($newId));
    exit;
}

$pageTitle = $isNew ? 'Add service (Pill)' : 'Edit service (Pill)';
tt_admin_header($pageTitle, 'services');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post">
  <div class="card">
    <h2>Hero service buttons</h2>
    <p class="field-hint">Displayed as scrolling pills on the home page banner</p>

    <input type="hidden" name="idx" value="<?= $isNew ? -1 : $idx ?>"/>

    <div class="grid-2">
      <div class="field"><label>Service name</label><input name="title" required value="<?= htmlspecialchars($service['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Long-tail boat rental"/></div>
      <div class="field"><label>ID (slug — optional)</label><input name="id" value="<?= htmlspecialchars($service['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="longtail-rental"/></div>
      <div class="field"><label>Link</label><input name="href" value="<?= htmlspecialchars($service['href'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="boats.html#longtail or https://..."/></div>
      <div class="field"><label>Icon</label>
        <select name="icon">
          <?php foreach ($iconOptions as $ic): ?>
            <option value="<?= htmlspecialchars($ic, ENT_QUOTES, 'UTF-8') ?>"<?= ($service['icon'] ?? '') === $ic ? ' selected' : '' ?>><?= htmlspecialchars($ic, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field" style="grid-column:1/-1">
        <label><input type="checkbox" name="external" value="1"<?= !empty($service['external']) ? ' checked' : '' ?>/> Open in new tab (external link)</label>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn btn-ghost" href="services.php">Back</a>
      <a class="btn btn-ghost" href="../index.html" target="_blank">View home page</a>
    </div>
  </div>
</form>

<?php tt_admin_footer(); ?>
