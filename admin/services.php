<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$services = $data['services'] ?? [];

if (isset($_GET['delete']) && $_GET['delete'] !== '') {
    $key = $_GET['delete'];
    $services = array_values(array_filter($services, function ($s, $i) use ($key) {
        $id = $s['id'] ?? (string)$i;
        return $id !== $key && (string)$i !== $key;
    }, ARRAY_FILTER_USE_BOTH));
    $data['services'] = $services;
    tt_write_data($data);
    tt_set_flash('Service deleted');
    header('Location: services.php');
    exit;
}

function tt_service_key(array $s, int $i): string
{
    return $s['id'] ?? (string)$i;
}

tt_admin_header('Services (Pills)', 'services');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="form-actions" style="border:0;margin-bottom:16px;padding:0">
  <a class="btn btn-primary" href="service-edit.php">+ Add service</a>
</div>

<div class="card">
  <?php if (!$services): ?>
    <p class="field-hint">No services yet — click the button above to add the first one</p>
  <?php else: ?>
  <table class="table">
    <thead>
      <tr><th>Service name</th><th>Link</th><th>Icon</th><th>Open in new tab</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($services as $i => $s):
        $key = tt_service_key($s, $i);
      ?>
        <tr>
          <td>
            <strong><?= htmlspecialchars($s['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
            <?php if (!empty($s['id'])): ?>
              <br><small class="field-hint"><?= htmlspecialchars($s['id'], ENT_QUOTES, 'UTF-8') ?></small>
            <?php endif; ?>
          </td>
          <td><small class="field-hint"><?= htmlspecialchars($s['href'] ?? '—', ENT_QUOTES, 'UTF-8') ?></small></td>
          <td><?= htmlspecialchars($s['icon'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= !empty($s['external']) ? 'Yes' : '—' ?></td>
          <td class="table-actions">
            <div class="table-actions-row">
              <a class="btn btn-ghost btn-sm" href="service-edit.php?key=<?= urlencode($key) ?>">Edit</a>
              <a class="btn btn-danger btn-sm" href="services.php?delete=<?= urlencode($key) ?>" onclick="return confirm('Delete this service?')">Delete</a>
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
