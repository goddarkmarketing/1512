<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
tt_require_admin();

$data = tt_read_data();
$videos = $data['videos'] ?? [];

if (isset($_GET['delete']) && $_GET['delete'] !== '') {
    $idx = (int)$_GET['delete'];
    if (isset($videos[$idx])) {
        array_splice($videos, $idx, 1);
        $data['videos'] = array_values($videos);
        tt_write_data($data);
        tt_set_flash('Video deleted');
    }
    header('Location: videos.php');
    exit;
}

tt_admin_header('Social videos', 'videos');
$flash = tt_flash();
if ($flash): ?><div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="form-actions" style="border:0;margin-bottom:16px;padding:0">
  <a class="btn btn-primary" href="video-edit.php">+ Add video</a>
</div>

<div class="card">
  <?php if (!$videos): ?>
    <p class="field-hint">No videos yet — click the button above to add the first one</p>
  <?php else: ?>
  <table class="table">
    <thead>
      <tr><th>Cover image</th><th>Platform</th><th>Video title</th><th>Views</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($videos as $i => $v):
        $plat = ($v['platform'] ?? '') === 'facebook' ? 'Facebook' : 'TikTok';
      ?>
        <tr>
          <td>
            <?php if (!empty($v['thumb'])): ?>
              <img src="<?= htmlspecialchars($v['thumb'], ENT_QUOTES, 'UTF-8') ?>" alt=""/>
            <?php else: ?>
              <span class="field-hint">—</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($plat, ENT_QUOTES, 'UTF-8') ?></td>
          <td>
            <strong><?= htmlspecialchars($v['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
            <?php if (!empty($v['id'])): ?>
              <br><small class="field-hint">#<?= (int)$v['id'] ?></small>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($v['views'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td class="table-actions">
            <div class="table-actions-row">
              <a class="btn btn-ghost btn-sm" href="video-edit.php?i=<?= $i ?>">Edit</a>
              <a class="btn btn-danger btn-sm" href="videos.php?delete=<?= $i ?>" onclick="return confirm('Delete this video?')">Delete</a>
              <a class="btn btn-ghost btn-sm" href="../videos.html" target="_blank">View</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php tt_admin_footer(); ?>
