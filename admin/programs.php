<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/package-collections.php';
tt_require_admin();

$data = tt_read_data();
$collections = tt_package_collections();

if (isset($_GET['delete'], $_GET['collection'])) {
    $collection = tt_package_collection_key($_GET['collection']);
    $id = trim((string)$_GET['delete']);
    if ($id !== '') {
        tt_package_delete($collection, $id, $data);
        tt_set_flash('Package deleted');
    }
    header('Location: programs.php#' . tt_package_section_hash($collection));
    exit;
}

tt_admin_header('Tour packages', 'programs');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="card">
  <p class="field-hint" style="margin:0">Manage home page cards and the <code>program.html</code> detail page — edit the content + 6 gallery images via the 「Edit」 button</p>
</div>

<?php
function tt_render_package_table(string $collection, array $items, string $sectionId, string $addLabel): void
{
    $collections = tt_package_collections();
    $meta = $collections[$collection];
    ?>
<section class="card admin-package-section" id="<?= htmlspecialchars($sectionId, ENT_QUOTES, 'UTF-8') ?>">
  <div class="admin-section-head">
    <div>
      <h2><?= htmlspecialchars($meta['label'], ENT_QUOTES, 'UTF-8') ?></h2>
      <p class="field-hint"><?= count($items) ?> packages</p>
    </div>
    <a class="btn btn-primary btn-sm" href="program-edit.php?collection=<?= urlencode($collection) ?>">+ <?= htmlspecialchars($addLabel, ENT_QUOTES, 'UTF-8') ?></a>
  </div>
  <?php if (!$items): ?>
    <p class="field-hint">No packages in this category yet</p>
  <?php else: ?>
  <table class="table">
    <thead>
      <tr><th>Package name</th><th>Code</th><th>Route</th><th>Price</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($items as $p):
          $pid = $p['id'] ?? '';
          ?>
        <tr>
          <td><strong><?= htmlspecialchars($p['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong></td>
          <td><?= ($p['packageCode'] ?? '') !== '' ? htmlspecialchars((string)$p['packageCode'], ENT_QUOTES, 'UTF-8') : '—' ?></td>
          <td><?= htmlspecialchars($p['route'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= number_format((int)($p['basePrice'] ?? 0)) ?> ฿</td>
          <td class="table-actions">
            <div class="table-actions-row">
              <a class="btn btn-primary btn-sm" href="program-edit.php?collection=<?= urlencode($collection) ?>&amp;id=<?= urlencode($pid) ?>" title="Edit detail page">Edit</a>
              <a class="btn btn-ghost btn-sm" href="../program.html?id=<?= urlencode($pid) ?>" target="_blank" rel="noopener">View</a>
              <a class="btn btn-danger btn-sm" href="programs.php?collection=<?= urlencode($collection) ?>&amp;delete=<?= urlencode($pid) ?>" onclick="return confirm('Delete this package?')">Delete</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</section>
<?php
}

tt_render_package_table('programs', $data['programs'] ?? [], 'daytrip', 'Add day trip');
tt_render_package_table('packages2d1n', $data['packages2d1n'] ?? [], 'overnight', 'Add 2 days 1 night');
tt_render_package_table('packages3d2n', $data['packages3d2n'] ?? [], 'packages3d2n', 'Add 3 days 2 nights');
tt_render_package_table('packages4d3n', $data['packages4d3n'] ?? [], 'packages4d3n', 'Add 4 days 3 nights');
?>

<?php tt_admin_footer(); ?>
