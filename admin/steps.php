<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$steps = $data['steps'] ?? [];

if (isset($_GET['delete']) && $_GET['delete'] !== '') {
    $idx = (int)$_GET['delete'];
    if (isset($steps[$idx])) {
        array_splice($steps, $idx, 1);
        $data['steps'] = array_values($steps);
        tt_write_data($data);
        tt_set_flash('Step deleted');
    }
    header('Location: steps.php');
    exit;
}

tt_admin_header('Booking steps', 'steps');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="form-actions" style="border:0;margin-bottom:16px;padding:0">
  <a class="btn btn-primary" href="step-edit.php">+ Add step</a>
</div>

<div class="card">
  <?php if (!$steps): ?>
    <p class="field-hint">No steps yet — click the button above to add the first one</p>
  <?php else: ?>
  <table class="table">
    <thead>
      <tr><th>Order</th><th>Title</th><th>Icon</th><th>Description</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($steps as $i => $s): ?>
        <tr>
          <td>#<?= (int)($s['n'] ?? $i + 1) ?></td>
          <td><strong><?= htmlspecialchars($s['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong></td>
          <td><?= htmlspecialchars($s['icon'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td><small class="field-hint"><?= htmlspecialchars(mb_substr(trim($s['desc'] ?? ''), 0, 50) . (mb_strlen(trim($s['desc'] ?? '')) > 50 ? '…' : ''), ENT_QUOTES, 'UTF-8') ?></small></td>
          <td class="table-actions">
            <div class="table-actions-row">
              <a class="btn btn-ghost btn-sm" href="step-edit.php?i=<?= $i ?>">Edit</a>
              <a class="btn btn-danger btn-sm" href="steps.php?delete=<?= $i ?>" onclick="return confirm('Delete this step?')">Delete</a>
              <a class="btn btn-ghost btn-sm" href="../index.html#home-steps" target="_blank">View</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php tt_admin_footer(); ?>
