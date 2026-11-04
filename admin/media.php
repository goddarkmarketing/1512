<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/image-sizes.php';
tt_require_admin();

$uploads = tt_list_uploads();

tt_admin_header('Upload image', 'media');
?>

<div class="card">
  <h2>Recommended image sizes (to fit the website)</h2>
  <p class="field-hint" style="margin-top:0">After uploading, click <strong>Copy path</strong> and paste it into the URL field of the relevant menu · Images are automatically center-cropped (object-fit: cover)</p>
  <table class="table" style="margin-top:12px">
    <thead>
      <tr><th>Used for</th><th>Recommended size</th><th>Aspect ratio</th><th>Edit in menu</th></tr>
    </thead>
    <tbody>
      <?php foreach (tt_image_size_guide_rows() as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><strong><?= htmlspecialchars($row['size'], ENT_QUOTES, 'UTF-8') ?></strong></td>
          <td><?= htmlspecialchars($row['ratio'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><small class="field-hint"><?= htmlspecialchars($row['where'], ENT_QUOTES, 'UTF-8') ?></small></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>Upload new image</h2>
  <p class="field-hint">JPG, PNG, WebP, GIF — max 5MB · If unsure, use the sizes in the table above</p>
  <form id="upload-form" enctype="multipart/form-data">
    <div class="field">
      <input type="file" name="file" id="file" accept="image/*" required/>
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
  </form>
  <div id="upload-result" class="field-hint" style="margin-top:12px"></div>
</div>

<div class="card">
  <h2>Uploaded images (<?= count($uploads) ?>)</h2>
  <?php if (!$uploads): ?>
    <p class="field-hint">No images yet — upload the first one above</p>
  <?php else: ?>
    <div class="media-grid">
      <?php foreach ($uploads as $u): ?>
        <div class="media-item">
          <img src="../<?= htmlspecialchars($u['url'], ENT_QUOTES, 'UTF-8') ?>" alt=""/>
          <code><?= htmlspecialchars($u['url'], ENT_QUOTES, 'UTF-8') ?></code>
          <button type="button" class="btn btn-ghost btn-sm" style="margin:8px;width:calc(100% - 16px)" data-copy="<?= htmlspecialchars($u['url'], ENT_QUOTES, 'UTF-8') ?>">Copy path</button>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script>
document.getElementById('upload-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData();
  fd.append('file', document.getElementById('file').files[0]);
  const res = await fetch('api/upload.php', { method: 'POST', body: fd });
  const json = await res.json();
  const el = document.getElementById('upload-result');
  if (json.ok) {
    el.innerHTML = 'Success: <code>' + json.url + '</code> — <a href="media.php">Refresh</a>';
    ttToast('Upload successful');
  } else {
    el.textContent = json.error || 'Error';
    ttToast(json.error || 'Error', true);
  }
});
</script>

<?php tt_admin_footer(); ?>
